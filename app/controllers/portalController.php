<?php

function getPortalRoles(): array
{
    return [
        [
            'id' => 'hospital-admin',
            'title' => 'Hospital Admin',
            'subtitle' => 'Login to Front Desk Module',
            'href' => 'hospital_admin_loginController.php',
            'cardClass' => 'card--admin',
        ],
        [
            'id' => 'doctor',
            'title' => 'Doctor',
            'subtitle' => 'Login to Doctor Module',
            'href' => 'doctor_loginController.php',
            'cardClass' => 'card--doctor',
        ],
        [
            'id' => 'nurse',
            'title' => 'Nurse',
            'subtitle' => 'Login to Nurse Module',
            'href' => 'nurse_loginController.php',
            'cardClass' => 'card--nurse',
        ],
    ];
}

$roles = getPortalRoles();

require __DIR__ . '/../views/portalView.php';