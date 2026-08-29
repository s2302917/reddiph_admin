<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Coordination Portal — Pulse Alert</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,900;1,400&family=Libre+Caslon+Text:ital@0;1&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/portal.css">
</head>

<body>

    <div class="portal">

        <header class="portal__topbar">
            <button type="button" class="back-btn" id="backBtn" aria-label="Go back">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

            <div class="brand-logo" aria-label="Pulse Alert">
                <div class="brand-logo__text">
                    <span class="brand-logo__line">PULSE</span>
                    <span class="brand-logo__line">ALERT</span>
                </div>
                <!-- Heartbeat / ECG pulse line at bottom-right corner -->
                <svg class="brand-logo__pulse" viewBox="0 0 36 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <polyline points="0,13 6,13 9,3 13,16 16,7 19,13 36,13"
                        stroke="#8d1018" stroke-width="1.4" fill="none"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </header>

        <main class="portal__main">
            <h1 class="portal__title">Emergency Coordination Portal</h1>
            <p class="portal__subtitle">Choose your role to continue</p>

            <div class="role-grid">
                <?php foreach ($roles as $role): ?>
                    <a href="<?php echo htmlspecialchars($role['href']); ?>"
                        class="role-card <?php echo htmlspecialchars($role['cardClass']); ?>"
                        data-role-id="<?php echo htmlspecialchars($role['id']); ?>">
                        <div class="role-card__body">
                            <h2 class="role-card__title"><?php echo htmlspecialchars($role['title']); ?></h2>
                            <p class="role-card__subtitle"><?php echo htmlspecialchars($role['subtitle']); ?></p>
                        </div>
                        <span class="role-card__arrow" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </main>

    </div>

    <script src="../../public/js/script.js"></script>
</body>

</html>