<?php

return "
CREATE TABLE groups (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    access_type ENUM('open', 'on_invitation', 'closed') NOT NULL DEFAULT 'open'
);
";