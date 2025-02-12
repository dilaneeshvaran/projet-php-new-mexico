<?php

return "

CREATE TABLE member_invitations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT NOT NULL,
    group_id INT NOT NULL,
    sent_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'accepted', 'declined') NOT NULL DEFAULT 'pending',
    INDEX (member_id),
    INDEX (group_id),
    CONSTRAINT fk_group FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    CONSTRAINT fk_member FOREIGN KEY (member_id) REFERENCES users(id) ON DELETE CASCADE
);
";