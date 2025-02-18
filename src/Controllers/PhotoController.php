<?php

namespace App\Controllers;

use App\Repositories\PageRepository;
use App\Repositories\PhotoRepository;
use App\Repositories\GroupRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserGroupRepository;
use App\Services\PhotoService;
use App\Services\GroupService;
use App\Models\Group;
use App\Core\View;
use App\Core\SQL;
use App\Core\Session;

class PhotoController {

    private PageRepository $pageRepository;

    private PhotoRepository $photoRepository;

    private PhotoService $photoService;

    private GroupRepository $groupRepository;

    private GroupService $groupService;

    private UserRepository $userRepository;

    private UserGroupRepository $userGroupRepository;
    
    public function __construct() {
        $db = new SQL();
        $this->userGroupRepository = new UserGroupRepository($db);
        $this->photoRepository = new PhotoRepository($db);
        $this->userRepository = new UserRepository($db);
        $this->photoService = new PhotoService($this->photoRepository,$this->userRepository);
        $this->pageRepository = new PageRepository($db);
        $this->groupRepository = new GroupRepository($db);
        $this->groupService = new GroupService($this->groupRepository, $this->userGroupRepository);
    }

    public function index(array $errors = [], array $formData = []): void
    {
        if(!(new Session())->isLogged()) {
            header('Location: /login');
            exit();
        }
        $groupId = $this->retrieveGroupId();
        $userId = (new Session())->getUserId();

        if (!$this->userGroupRepository->isMember((int)$groupId, (int)$userId)) {
            header('Location: /');
            exit();
        }
        $this->renderView();
    }

    private function renderView(array $errors = [], array $formData = []): void
    {
        $pageId = 1;
        $pageData = $this->pageRepository->findOneById($pageId);

        //alternative values if $pageData is null
        $title = $pageData ? $pageData->getTitle() : "Photos";
        $description = $pageData ? $pageData->getDescription() : "Voir les photos";
        $content = $pageData ? $pageData->getContent() : "";

        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $groupId = $this->retrieveGroupId();
        $userId = $_SESSION['user_id'] ?? 0;

        //check if user is the creator of the photo or admin of the group
        $userRole = $this->userGroupRepository->getUserRole($groupId, $userId);
        
        $groupAccess = $this->userGroupRepository->getGroupAccess($groupId, $userId) ?? "No Access";
        $photos = $this->photoService->fetchPhotosByGroupId($groupId);
        $group = $this->groupService->getGroupById($groupId);
        
        $view = new View("photo/group_photos.php", "front.php");
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("photos", $photos);
        $view->addData("group", $group);
        $view->addData("userRole", $userRole);
        $view->addData("group_access", $groupAccess);
        $view->addData("userId", $userId);
        echo $view->render();
    }

    private function retrieveGroupId(): int {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST['groupId'] ?? 0;
        } 

        $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $groupIndex = array_search('group', $urlParts);
        return ($groupIndex !== false && isset($urlParts[$groupIndex + 1])) ? (int)$urlParts[$groupIndex + 1] : 0;
    }


    //SHARE PUBLIC PHOTO
    public function generateShareLink(): void {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'request methode Invalide']);
            exit;
        }
    
        $jsonData = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($jsonData['photoId']) || !isset($jsonData['csrf_token'])) {
            echo json_encode(['error' => 'Erreurs avec les données envoyées']);
            exit;
        }
    
        if (!isset($_SESSION['csrf_token']) || $jsonData['csrf_token'] !== $_SESSION['csrf_token']) {
            echo json_encode(['error' => 'Session Invalide']);
            exit;
        }
    
        $photoId = (int)$jsonData['photoId'];
        
        $photo = $this->photoRepository->findById($photoId);
        if (!$photo) {
            echo json_encode(['error' => 'Erruer: Photo Invalide']);
            exit;
        }
    
        $token = bin2hex(random_bytes(32));
        
        try {
            $success = $this->photoRepository->savePublicToken($photoId, $token);
            
            if ($success) {
                //get protocol
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
                
                //get domain and port if exist
                $domain = $_SERVER['HTTP_HOST'] ?: 'localhost:8000';
                
                $shareLink = sprintf('%s://%s/photo/shared/%s',
                    $protocol,
                    $domain,
                    $token
                );
                echo json_encode(['success' => true, 'link' => $shareLink]);
            } else {
                echo json_encode(['error' => 'Erreur lors de la génération du lien de partage']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'Erreur: ' . $e->getMessage()]);
        }
        exit;
    }
    
    public function viewSharedPhoto(): void {
        $token = $this->retrieveToken();
        try {
            $photo = $this->photoRepository->findByPublicToken($token);
            
            if (!$photo) {
                header('Location: /404');
                exit;
            }
    
            $view = new View("photo/shared_photo.php", "front.php");
            $view->addData("photo", $photo);
            $view->addData("title", "Photo Partagée");
            $view->addData("description", "Photo Partagée");
            echo $view->render();
        } catch (Exception $e) {
            header('Location: /404');
            exit;
        }
    }

    private function retrieveToken(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST['token'] ?? '';
        } 
        $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

        $tokenIndex = array_search('shared', $urlParts);
        if ($tokenIndex !== false && isset($urlParts[$tokenIndex + 1])) {
            return $urlParts[$tokenIndex + 1];
        }

        return '';
    }
}