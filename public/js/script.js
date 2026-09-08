// =========================================================
// REDDI PH / PULSE ALERT
// GLOBAL PAGE SCRIPT
// =========================================================
//
// Contains:
// 1. Global back button behavior
// 2. Forgot password modals
// 3. Form prototypes
// 4. Signup interactions
// 5. Incoming Dispatches behavior
// 6. Dashboard behavior
// 7. Emergency Alerts behavior
//
// No database or business logic belongs here.
// =========================================================


// =========================================================
// PAGE INITIALIZATION
// =========================================================

document.addEventListener('DOMContentLoaded', function () {

    // =====================================================
    // GLOBAL BACK BUTTON
    // =====================================================

    var backButtons = document.querySelectorAll(
        '#backBtn, ' +
        '#hospitaladminBackBtn, ' +
        '#doctorBackBtn, ' +
        '#nurseBackBtn, ' +
        '.back-btn, ' +
        '.login-back-btn, ' +
        '.hospitaladmin-login-back-btn, ' +
        '.doctor-login-back-btn, ' +
        '.nurse-login-back-btn'
    );

    backButtons.forEach(function (btn) {

        btn.addEventListener('click', function (event) {

            event.preventDefault();

            if (window.history.length > 1) {

                window.history.back();

            } else {

                window.location.href = '/';

            }

        });

    });


    // =====================================================
    // FORGOT PASSWORD MODALS
    // =====================================================

    function setupForgotPasswordModal(role) {

        var modalId =
            role === 'admin'
                ? 'adminForgotPassModal'
                : role + 'ForgotPassModal';

        var linkId =
            role === 'admin'
                ? 'hospitaladminForgotPassLink'
                : role + 'ForgotPassLink';

        var closeId =
            role === 'admin'
                ? 'adminForgotPassBackBtn'
                : role + 'ForgotPassBackBtn';

        var emailId =
            role === 'admin'
                ? 'adminforgotpass-email'
                : role + 'forgotpass-email';


        var modal =
            document.getElementById(modalId);

        var link =
            document.getElementById(linkId);

        var closeBtn =
            document.getElementById(closeId);


        // -------------------------------------------------
        // OPEN MODAL
        // -------------------------------------------------

        function openModal() {

            if (!modal) {
                return;
            }

            modal.classList.add('is-open');

            modal.setAttribute(
                'aria-hidden',
                'false'
            );


            var emailInput =
                document.getElementById(emailId);

            if (emailInput) {

                window.setTimeout(function () {

                    emailInput.focus();

                }, 150);

            }

        }


        // -------------------------------------------------
        // CLOSE MODAL
        // -------------------------------------------------

        function closeModal() {

            if (!modal) {
                return;
            }

            modal.classList.remove('is-open');

            modal.setAttribute(
                'aria-hidden',
                'true'
            );

        }


        // -------------------------------------------------
        // FORGOT PASSWORD LINK
        // -------------------------------------------------

        if (link) {

            link.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();

                    openModal();

                }
            );

        }


        // -------------------------------------------------
        // MODAL BACK / CLOSE BUTTON
        // -------------------------------------------------

        if (closeBtn) {

            closeBtn.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();

                    closeModal();

                }
            );

        }


        // -------------------------------------------------
        // CLICK OUTSIDE MODAL
        // -------------------------------------------------

        if (modal) {

            modal.addEventListener(
                'click',
                function (event) {

                    if (event.target === modal) {

                        closeModal();

                    }

                }
            );

        }


        return {
            open: openModal,
            close: closeModal
        };

    }


    var adminForgotModal =
        setupForgotPasswordModal('admin');

    var doctorForgotModal =
        setupForgotPasswordModal('doctor');

    var nurseForgotModal =
        setupForgotPasswordModal('nurse');


    // =====================================================
    // ESC KEY — CLOSE ALL FORGOT PASSWORD MODALS
    // =====================================================

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' ||
                event.key === 'Esc'
            ) {

                adminForgotModal.close();
                doctorForgotModal.close();
                nurseForgotModal.close();

            }

        }
    );


    // =====================================================
    // FORGOT PASSWORD FORM SUBMISSION
    // =====================================================

    function setupForgotPasswordForm(
        role,
        modalHandler
    ) {

        var formId =
            role === 'admin'
                ? 'adminForgotPassForm'
                : role + 'ForgotPassForm';

        var emailId =
            role === 'admin'
                ? 'adminforgotpass-email'
                : role + 'forgotpass-email';


        var form =
            document.getElementById(formId);


        if (!form) {
            return;
        }


        form.addEventListener(
            'submit',
            function (event) {

                var action =
                    form.getAttribute('action');


                // Only use prototype behavior
                // when no real backend action exists.

                if (
                    !action ||
                    action === '#'
                ) {

                    event.preventDefault();


                    var emailInput =
                        document.getElementById(emailId);


                    var email =
                        emailInput
                            ? emailInput.value
                            : '';


                    alert(
                        'Reset link request sent for ' +
                        email +
                        ' (prototype).'
                    );


                    modalHandler.close();

                }

            }
        );

    }


    setupForgotPasswordForm(
        'admin',
        adminForgotModal
    );

    setupForgotPasswordForm(
        'doctor',
        doctorForgotModal
    );

    setupForgotPasswordForm(
        'nurse',
        nurseForgotModal
    );


    // =====================================================
    // CONTACT FORM PROTOTYPE
    // =====================================================

    var contactForm =
        document.querySelector(
            '.contact-form'
        );


    if (contactForm) {

        contactForm.addEventListener(
            'submit',
            function (event) {

                event.preventDefault();

                alert(
                    'Thanks! This is a static prototype — ' +
                    'hook this form up to your backend.'
                );

            }
        );

    }


    // =====================================================
    // LOGIN FORM PROTOTYPE
    // =====================================================

    var loginForms =
        document.querySelectorAll(
            '#loginForm, ' +
            '#hospitaladminLoginForm, ' +
            '#doctorLoginForm, ' +
            '#nurseLoginForm, ' +
            '.login-form, ' +
            '.hospitaladmin-login-form, ' +
            '.doctor-login-form, ' +
            '.nurse-login-form'
        );


    loginForms.forEach(function (form) {

        form.addEventListener(
            'submit',
            function (event) {

                var action =
                    form.getAttribute('action');


                if (
                    !action ||
                    action === '#'
                ) {

                    event.preventDefault();

                    console.log(
                        'Login form submitted'
                    );

                }

            }
        );

    });


    // =====================================================
    // SIGNUP INTERACTIONS
    // =====================================================

    function setupSignupForm(role) {

        var formId =
            role + 'SignupForm';

        var shellSelector =
            '.' + role + '-signup-shell';

        var buttonSelector =
            '.' + role + '-signup-submit';

        var fieldSelector =
            '.' + role + '-signup-field input';


        // -------------------------------------------------
        // SHOW SIGNUP SHELL
        // -------------------------------------------------

        var shell =
            document.querySelector(
                shellSelector
            );


        if (shell) {

            shell.classList.add(
                'is-visible'
            );

        }


        // -------------------------------------------------
        // SIGNUP SUBMISSION
        // -------------------------------------------------

        var form =
            document.getElementById(formId);


        if (form) {

            form.addEventListener(
                'submit',
                function () {

                    var submitButton =
                        form.querySelector(
                            buttonSelector
                        );


                    if (submitButton) {

                        submitButton.disabled =
                            true;

                        submitButton.textContent =
                            'Creating account...';

                        submitButton.style.opacity =
                            '0.9';

                    }

                }
            );

        }


        // -------------------------------------------------
        // INPUT FOCUS
        // -------------------------------------------------

        var inputs =
            document.querySelectorAll(
                fieldSelector
            );


        inputs.forEach(function (input) {

            input.addEventListener(
                'focus',
                function () {

                    if (input.parentElement) {

                        input.parentElement.classList.add(
                            'is-focused'
                        );

                    }

                }
            );


            input.addEventListener(
                'blur',
                function () {

                    if (input.parentElement) {

                        input.parentElement.classList.remove(
                            'is-focused'
                        );

                    }

                }
            );

        });

    }


    setupSignupForm('nurse');
    setupSignupForm('doctor');
    setupSignupForm('hospitaladmin');


    // =====================================================
    // INCOMING DISPATCHES
    // =====================================================

    if (
        document.getElementById(
            'incomingdispatch-barangay-grid'
        ) ||
        document.getElementById(
            'incomingdispatch-search-input'
        )
    ) {

        incomingdispatchAnimateFacilityBars();

        incomingdispatchAnimateCards();

        incomingdispatchPulseActiveCards();

        incomingdispatchBindSearch();

        incomingdispatchBindSearchFocus();

        incomingdispatchBindBell();

        incomingdispatchBindBellShake();

        incomingdispatchBindQuickActions();

        incomingdispatchBindCardClicks();

    }


    // =====================================================
    // DASHBOARD
    // =====================================================

    if (
        document.getElementById(
            'dashboard-response-chart'
        ) ||
        document.getElementById(
            'dashboard-hospital-grid'
        )
    ) {

        dashboardAnimateMetricBars();

        dashboardAnimateHospitalBars();

        dashboardInitChart();

        dashboardBindSearchFocus();

        dashboardBindBellShake();

        dashboardBindUserMenu();

    }


    // =====================================================
    // EMERGENCY ALERTS
    // =====================================================

    if (
        document.getElementById(
            'emergency_alert_shell'
        ) ||
        document.getElementById(
            'emergency_alert_table'
        )
    ) {

        emergencyAlertInit();

    }

});


// =========================================================
// INCOMING DISPATCHES
// =========================================================


// =========================================================
// FACILITY STATUS BARS
// =========================================================

function incomingdispatchAnimateFacilityBars() {

    var bars =
        document.querySelectorAll(
            '.incomingdispatch-status-bar-fill[data-percent], ' +
            '.incomingdispatch-metric-bar-fill[data-percent]'
        );


    bars.forEach(function (bar) {

        var percent =
            parseFloat(
                bar.getAttribute('data-percent')
            ) || 0;


        percent =
            Math.max(
                0,
                Math.min(100, percent)
            );


        bar.style.width = '0%';


        window.requestAnimationFrame(
            function () {

                window.setTimeout(
                    function () {

                        bar.style.width =
                            percent + '%';

                    },
                    150
                );

            }
        );

    });

}


// =========================================================
// CARD ENTRANCE ANIMATION
// =========================================================

function incomingdispatchAnimateCards() {

    var cards =
        document.querySelectorAll(
            '#incomingdispatch-barangay-grid ' +
            '.incomingdispatch-card'
        );


    cards.forEach(
        function (card, index) {

            card.classList.add(
                'incomingdispatch-fade-in'
            );


            card.style.animationDelay =
                (index * 35) + 'ms';

        }
    );

}


// =========================================================
// ACTIVE CARD PULSE
// =========================================================

function incomingdispatchPulseActiveCards() {

    var cards =
        document.querySelectorAll(
            '#incomingdispatch-barangay-grid ' +
            '.incomingdispatch-card--active'
        );


    cards.forEach(function (card) {

        card.classList.add(
            'incomingdispatch-card--pulse'
        );

    });

}


// =========================================================
// SEARCH FILTER
// =========================================================

function incomingdispatchBindSearch() {

    var input =
        document.getElementById(
            'incomingdispatch-search-input'
        );


    if (!input) {
        return;
    }


    input.addEventListener(
        'input',
        function () {

            var query =
                input.value
                    .trim()
                    .toLowerCase();


            var cards =
                document.querySelectorAll(
                    '#incomingdispatch-barangay-grid ' +
                    '.incomingdispatch-card'
                );


            cards.forEach(
                function (card) {

                    var nameElement =
                        card.querySelector(
                            '.incomingdispatch-card-name'
                        );


                    if (!nameElement) {
                        return;
                    }


                    var name =
                        nameElement.textContent
                            .trim()
                            .toLowerCase();


                    var matches =
                        query === '' ||
                        name.indexOf(query) !== -1;


                    card.style.display =
                        matches ? '' : 'none';

                }
            );

        }
    );

}


// =========================================================
// SEARCH FOCUS
// =========================================================

function incomingdispatchBindSearchFocus() {

    var input =
        document.getElementById(
            'incomingdispatch-search-input'
        );


    if (!input) {
        return;
    }


    input.addEventListener(
        'focus',
        function () {

            this.style.transition =
                'width 0.2s ease';

            this.style.width =
                '340px';

        }
    );


    input.addEventListener(
        'blur',
        function () {

            this.style.width =
                '300px';

        }
    );

}


// =========================================================
// NOTIFICATION BELL
// =========================================================

function incomingdispatchBindBell() {

    var bell =
        document.getElementById(
            'incomingdispatch-bell-btn'
        );

    var dot =
        document.getElementById(
            'incomingdispatch-bell-dot'
        );


    if (!bell) {
        return;
    }


    bell.addEventListener(
        'click',
        function () {

            if (!dot) {
                return;
            }


            dot.style.display =
                dot.style.display === 'none'
                    ? ''
                    : 'none';

        }
    );

}


// =========================================================
// BELL SHAKE
// =========================================================

function incomingdispatchBindBellShake() {

    var bell =
        document.getElementById(
            'incomingdispatch-bell-btn'
        );

    var dot =
        document.getElementById(
            'incomingdispatch-bell-dot'
        );


    if (!bell || !dot) {
        return;
    }


    bell.style.transition =
        'transform 0.15s ease';


    window.setTimeout(
        function () {

            var ticks = 0;


            var shake =
                window.setInterval(
                    function () {

                        bell.style.transform =
                            ticks % 2 === 0
                                ? 'rotate(-10deg)'
                                : 'rotate(10deg)';


                        ticks++;


                        if (ticks > 3) {

                            window.clearInterval(
                                shake
                            );


                            bell.style.transform =
                                'rotate(0deg)';

                        }

                    },
                    90
                );

        },
        700
    );

}


// =========================================================
// QUICK ACTIONS
// =========================================================

function incomingdispatchBindQuickActions() {

    var broadcastButton =
        document.getElementById(
            'incomingdispatch-btn-broadcast'
        );

    var exportButton =
        document.getElementById(
            'incomingdispatch-btn-export'
        );


    // -----------------------------------------------------
    // BROADCAST
    // -----------------------------------------------------

    if (broadcastButton) {

        broadcastButton.addEventListener(
            'click',
            function () {

                broadcastButton.classList.add(
                    'incomingdispatch-card--pulse'
                );


                window.setTimeout(
                    function () {

                        broadcastButton.classList.remove(
                            'incomingdispatch-card--pulse'
                        );

                    },
                    1400
                );

            }
        );

    }


    // -----------------------------------------------------
    // EXPORT
    // -----------------------------------------------------

    if (exportButton) {

        exportButton.addEventListener(
            'click',
            function () {

                exportButton.style.transform =
                    'scale(0.97)';


                window.setTimeout(
                    function () {

                        exportButton.style.transform =
                            '';

                    },
                    150
                );

            }
        );

    }

}


// =========================================================
// BARANGAY CARD CLICK
// =========================================================

function incomingdispatchBindCardClicks() {

    var cards =
        document.querySelectorAll(
            '#incomingdispatch-barangay-grid ' +
            '.incomingdispatch-card'
        );


    cards.forEach(function (card) {

        card.addEventListener(
            'click',
            function () {

                card.style.transform =
                    'scale(0.97)';


                window.setTimeout(
                    function () {

                        card.style.transform =
                            '';

                    },
                    120
                );


                var nameElement =
                    card.querySelector(
                        '.incomingdispatch-card-name'
                    );


                var name =
                    nameElement
                        ? nameElement.textContent.trim()
                        : '';


                var count =
                    card.getAttribute(
                        'data-count'
                    );


                console.log(
                    '[incomingdispatch]',
                    'Barangay:',
                    name,
                    '| Active dispatches:',
                    count
                );

            }
        );

    });

}


// =========================================================
// DASHBOARD
// =========================================================


// =========================================================
// DASHBOARD METRIC ANIMATION
// =========================================================

function dashboardAnimateMetricBars() {

    var bars =
        document.querySelectorAll(
            '.dashboard-metric-bar-fill[data-percent]'
        );


    bars.forEach(function (bar) {

        var percent =
            parseFloat(
                bar.getAttribute('data-percent')
            ) || 0;


        percent =
            Math.max(
                0,
                Math.min(100, percent)
            );


        bar.style.width =
            '0%';


        window.requestAnimationFrame(
            function () {

                window.setTimeout(
                    function () {

                        bar.style.width =
                            percent + '%';

                    },
                    120
                );

            }
        );

    });

}


// =========================================================
// HOSPITAL BED CAPACITY BARS
// =========================================================

function dashboardAnimateHospitalBars() {

    var bars =
        document.querySelectorAll(
            '.dashboard-bed-fill[data-percent], ' +
            '.dashboard-hospital-bar-fill[data-percent]'
        );


    bars.forEach(function (bar) {

        var percent =
            parseFloat(
                bar.getAttribute('data-percent')
            ) || 0;


        percent =
            Math.max(
                0,
                Math.min(100, percent)
            );


        bar.style.width =
            '0%';


        window.requestAnimationFrame(
            function () {

                window.setTimeout(
                    function () {

                        bar.style.width =
                            percent + '%';

                    },
                    120
                );

            }
        );

    });

}


// =========================================================
// DASHBOARD RESPONSE CHART
// =========================================================

function dashboardInitChart() {

    var canvas =
        document.getElementById(
            'dashboard-response-chart'
        );


    if (!canvas) {
        return;
    }


    // -----------------------------------------------------
    // NEW CHART DATA FORMAT
    // -----------------------------------------------------

    var dataElement =
        document.getElementById(
            'dashboard-chart-data'
        );


    if (dataElement) {

        var data;


        try {

            data =
                JSON.parse(
                    dataElement.textContent || '{}'
                );

        } catch (error) {

            console.error(
                '[dashboard] Invalid chart data.',
                error
            );

            return;

        }


        drawResponseChart(
            canvas,
            data.labels || [],
            data.values || [],
            Number(data.max) || 25
        );


        window.addEventListener(
            'resize',
            function () {

                drawResponseChart(
                    canvas,
                    data.labels || [],
                    data.values || [],
                    Number(data.max) || 25
                );

            }
        );


        return;

    }


    // -----------------------------------------------------
    // OLD CHART DATA FORMAT
    // -----------------------------------------------------

    var dataString =
        canvas.getAttribute(
            'data-chart-data'
        );


    if (!dataString) {
        return;
    }


    try {

        var oldData =
            JSON.parse(dataString);


        drawResponseChart(
            canvas,
            oldData.labels || [],
            oldData.values || [],
            Number(oldData.max) || 25
        );


    } catch (error) {

        console.error(
            '[dashboard] Failed to initialize chart.',
            error
        );

    }

}


// =========================================================
// DRAW RESPONSE PERFORMANCE CHART
// =========================================================

function drawResponseChart(
    canvas,
    labels,
    values,
    maxValue
) {

    var context =
        canvas.getContext('2d');


    if (!context) {
        return;
    }


    var rect =
        canvas.getBoundingClientRect();


    var dpr =
        window.devicePixelRatio || 1;


    var width =
        Math.max(
            280,
            Math.floor(rect.width)
        );


    var height =
        Math.max(
            190,
            Math.floor(rect.height)
        );


    canvas.width =
        width * dpr;

    canvas.height =
        height * dpr;


    context.setTransform(
        dpr,
        0,
        0,
        dpr,
        0,
        0
    );


    context.clearRect(
        0,
        0,
        width,
        height
    );


    var padding = {
        top: 14,
        right: 14,
        bottom: 34,
        left: 31
    };


    var chartWidth =
        width -
        padding.left -
        padding.right;


    var chartHeight =
        height -
        padding.top -
        padding.bottom;


    var steps = 5;


    // -----------------------------------------------------
    // GRID / Y AXIS
    // -----------------------------------------------------

    context.font =
        '10px Inter, Arial, sans-serif';

    context.textAlign =
        'right';

    context.textBaseline =
        'middle';

    context.strokeStyle =
        '#eadfd9';

    context.lineWidth =
        1;

    context.fillStyle =
        '#7c7474';


    for (
        var i = 0;
        i <= steps;
        i++
    ) {

        var y =
            padding.top +
            chartHeight -
            (
                chartHeight *
                i /
                steps
            );


        var value =
            Math.round(
                maxValue *
                i /
                steps
            );


        context.beginPath();

        context.moveTo(
            padding.left,
            y
        );

        context.lineTo(
            width - padding.right,
            y
        );

        context.stroke();


        context.fillText(
            String(value),
            padding.left - 7,
            y
        );

    }


    if (
        !labels.length ||
        !values.length
    ) {
        return;
    }


    // -----------------------------------------------------
    // DATA POINTS
    // -----------------------------------------------------

    var points = [];


    var denominator =
        Math.max(
            1,
            labels.length - 1
        );


    values.forEach(
        function (value, index) {

            var x =
                padding.left +
                (
                    chartWidth *
                    index /
                    denominator
                );


            var clamped =
                Math.max(
                    0,
                    Math.min(
                        maxValue,
                        Number(value) || 0
                    )
                );


            var y =
                padding.top +
                chartHeight -
                (
                    clamped /
                    maxValue *
                    chartHeight
                );


            points.push({
                x: x,
                y: y,
                value: clamped,
                label:
                    labels[index] || ''
            });

        }
    );


    // -----------------------------------------------------
    // X AXIS LABELS
    // -----------------------------------------------------

    context.textAlign =
        'center';

    context.textBaseline =
        'top';

    context.fillStyle =
        '#817878';


    labels.forEach(
        function (label, index) {

            var x =
                padding.left +
                (
                    chartWidth *
                    index /
                    denominator
                );


            context.fillText(
                String(label),
                x,
                height -
                padding.bottom +
                8
            );

        }
    );


    // -----------------------------------------------------
    // SOFT CHART AREA
    // -----------------------------------------------------

    context.beginPath();

    context.moveTo(
        points[0].x,
        padding.top + chartHeight
    );


    points.forEach(
        function (point) {

            context.lineTo(
                point.x,
                point.y
            );

        }
    );


    context.lineTo(
        points[points.length - 1].x,
        padding.top + chartHeight
    );


    context.closePath();


    context.fillStyle =
        'rgba(129, 29, 25, 0.055)';

    context.fill();


    // -----------------------------------------------------
    // CHART LINE
    // -----------------------------------------------------

    context.beginPath();


    points.forEach(
        function (point, index) {

            if (index === 0) {

                context.moveTo(
                    point.x,
                    point.y
                );

            } else {

                context.lineTo(
                    point.x,
                    point.y
                );

            }

        }
    );


    context.strokeStyle =
        '#811d19';

    context.lineWidth =
        2.5;

    context.lineJoin =
        'round';

    context.lineCap =
        'round';

    context.stroke();


    // -----------------------------------------------------
    // CHART POINTS
    // -----------------------------------------------------

    points.forEach(
        function (point) {

            context.beginPath();

            context.arc(
                point.x,
                point.y,
                3.4,
                0,
                Math.PI * 2
            );


            context.fillStyle =
                '#811d19';

            context.fill();


            context.beginPath();

            context.arc(
                point.x,
                point.y,
                1.4,
                0,
                Math.PI * 2
            );


            context.fillStyle =
                '#fff';

            context.fill();

        }
    );

}


// =========================================================
// DASHBOARD SEARCH
// =========================================================

function dashboardBindSearchFocus() {

    var input =
        document.getElementById(
            'dashboard-search-input'
        );


    if (!input) {
        return;
    }


    input.addEventListener(
        'focus',
        function () {

            this.classList.add(
                'dashboard-search--focused'
            );

        }
    );


    input.addEventListener(
        'blur',
        function () {

            this.classList.remove(
                'dashboard-search--focused'
            );

        }
    );


    input.addEventListener(
        'input',
        function () {

            var query =
                this.value
                    .trim()
                    .toLowerCase();


            // -------------------------------------------------
            // ALERT ROWS
            // -------------------------------------------------

            document.querySelectorAll(
                '.dashboard-alert-row'
            ).forEach(
                function (row) {

                    var matches =
                        query === '' ||
                        row.textContent
                            .toLowerCase()
                            .indexOf(query) !== -1;


                    row.style.display =
                        matches ? '' : 'none';

                }
            );


            // -------------------------------------------------
            // HOSPITAL CARDS
            // -------------------------------------------------

            document.querySelectorAll(
                '.dashboard-hospital-card'
            ).forEach(
                function (card) {

                    var matches =
                        query === '' ||
                        card.textContent
                            .toLowerCase()
                            .indexOf(query) !== -1;


                    card.style.display =
                        matches ? '' : 'none';

                }
            );

        }
    );

}


// =========================================================
// DASHBOARD NOTIFICATION BELL
// =========================================================

function dashboardBindBellShake() {

    var bell =
        document.getElementById(
            'dashboard-bell-btn'
        );

    var dot =
        document.getElementById(
            'dashboard-bell-dot'
        );


    if (!bell || !dot) {
        return;
    }


    // -----------------------------------------------------
    // TOGGLE NOTIFICATION DOT
    // -----------------------------------------------------

    bell.addEventListener(
        'click',
        function () {

            dot.style.display =
                dot.style.display === 'none'
                    ? ''
                    : 'none';

        }
    );


    // -----------------------------------------------------
    // INITIAL SHAKE
    // -----------------------------------------------------

    window.setTimeout(
        function () {

            var ticks = 0;


            var shake =
                window.setInterval(
                    function () {

                        bell.style.transform =
                            ticks % 2 === 0
                                ? 'rotate(-8deg)'
                                : 'rotate(8deg)';


                        ticks++;


                        if (ticks > 3) {

                            window.clearInterval(
                                shake
                            );


                            bell.style.transform =
                                'rotate(0deg)';

                        }

                    },
                    85
                );

        },
        650
    );

}


// =========================================================
// DASHBOARD USER MENU
// =========================================================

function dashboardBindUserMenu() {

    var userMenu =
        document.getElementById(
            'dashboard-user-menu'
        );


    if (!userMenu) {
        return;
    }


    userMenu.addEventListener(
        'click',
        function () {

            console.log(
                'User menu clicked - ready for dropdown implementation'
            );

        }
    );

}


// =========================================================
// EMERGENCY ALERTS
// =========================================================

var emergencyAlertToastTimer = null;
var emergencyAlertSearchTimer = null;

function emergencyAlertInit() {
    emergencyAlertBindSearch();
    emergencyAlertBindFilters();
    emergencyAlertBindSummaryCards();
    emergencyAlertBindBell();
    emergencyAlertBindModal();
    emergencyAlertBindNavigation();
    emergencyAlertBindActions();
    emergencyAlertLoadData();
}

function emergencyAlertControllerUrl() {
    return '/reddiph_admin/app/controllers/emergency_alertController.php';
}

function emergencyAlertLoadData() {
    var initialDataElement = document.getElementById('emergency_alert_initial_data');
    if (initialDataElement) {
        try {
            var initialData = JSON.parse(initialDataElement.textContent || '{}');
            if (initialData && Array.isArray(initialData.alerts)) {
                window.emergency_alert_data = initialData;
                emergencyAlertUpdateHeader(initialData);
                emergencyAlertUpdateSummary(initialData.summary || {});
                return;
            }
        } catch (e) {
            console.warn('[Emergency Alerts] Could not parse embedded data, fetching from server...', e);
        }
    }

    var url = new URL(emergencyAlertControllerUrl(), window.location.origin);
    url.searchParams.set('action', 'data');

    fetch(url.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Unable to load emergency alerts.');
            }
            return response.json();
        })
        .then(function (data) {
            if (!data.success) {
                throw new Error(data.message || 'Unable to load emergency alerts.');
            }

            window.emergency_alert_data = data;
            emergencyAlertUpdateHeader(data);
            emergencyAlertUpdateSummary(data.summary || {});
            emergencyAlertRender('all');
        })
        .catch(function (error) {
            console.error('[Emergency Alerts]', error);
            emergencyAlertShowToast('Showing current emergency alert data.');
            emergencyAlertSetActive('all');
            emergencyAlertStaggerRows();
        });
}

function emergencyAlertUpdateHeader(data) {
    var subtitle = document.getElementById('emergency_alert_subtitle');
    var name = document.getElementById('emergency_alert_user_name');
    var role = document.getElementById('emergency_alert_user_role');

    if (subtitle && data.pageTitle) subtitle.textContent = data.pageTitle;
    if (name && data.coordinatorName) name.textContent = data.coordinatorName;
    if (role && data.coordinatorRole) role.textContent = data.coordinatorRole;
}

function emergencyAlertUpdateSummary(summary) {
    var values = {
        critical: summary.critical || 0,
        dispatched: summary.dispatched || 0,
        stable: summary.stable || 0,
        resolved: summary.resolvedToday || 0
    };

    Object.keys(values).forEach(function (key) {
        var el = document.getElementById('emergency_alert_summary_' + key + '_value');
        if (el) el.textContent = values[key];
    });

    var dot = document.getElementById('emergency_alert_bell_dot');
    if (dot) dot.hidden = Number(values.critical) <= 0;

    emergencyAlertAnimateSummaryCards();

    if (Number(values.critical) > 0) {
        emergencyAlertPulseBell();
    }
}

function emergencyAlertBindSearch() {
    var input = document.getElementById('emergency_alert_search_input');
    var wrap = document.getElementById('emergency_alert_search_wrap');

    if (!input) return;

    input.addEventListener('input', function () {
        window.clearTimeout(emergencyAlertSearchTimer);
        emergencyAlertSearchTimer = window.setTimeout(function () {
            emergencyAlertRender(emergencyAlertCurrentStatus());
        }, 60);
    });

    input.addEventListener('focus', function () {
        if (wrap) wrap.classList.add('emergency_alert_search_focused');
    });

    input.addEventListener('blur', function () {
        if (wrap) wrap.classList.remove('emergency_alert_search_focused');
    });
}

function emergencyAlertBindFilters() {
    document.querySelectorAll('.emergency_alert_filter').forEach(function (button) {
        button.addEventListener('click', function () {
            emergencyAlertRender(this.getAttribute('data-status') || 'all');
        });
    });
}

function emergencyAlertBindSummaryCards() {
    document.querySelectorAll('.emergency_alert_summary_card').forEach(function (card) {
        card.addEventListener('click', function () {
            var status = this.getAttribute('data-status') || 'all';

            if (status === 'resolved') {
                emergencyAlertShowToast('Resolved-today history is not part of the active-alert table.');
                return;
            }

            emergencyAlertRender(status);
        });
    });
}

function emergencyAlertCurrentStatus() {
    var active = document.querySelector('.emergency_alert_filter_active');
    return active ? (active.getAttribute('data-status') || 'all') : 'all';
}

function emergencyAlertSetActive(status) {
    document.querySelectorAll('.emergency_alert_filter').forEach(function (button) {
        var active = button.getAttribute('data-status') === status;
        button.classList.toggle('emergency_alert_filter_active', active);
        button.classList.toggle('emergency_alert_filter_active_critical', active && status === 'critical');
        button.classList.toggle('emergency_alert_filter_active_dispatched', active && status === 'dispatched');
        button.classList.toggle('emergency_alert_filter_active_stable', active && status === 'stable');
    });

    document.querySelectorAll('.emergency_alert_summary_card').forEach(function (card) {
        var cardStatus = card.getAttribute('data-status');
        var active = (status !== 'all' && cardStatus === status);

        card.classList.remove(
            'emergency_alert_summary_card_active',
            'emergency_alert_summary_card_critical_active',
            'emergency_alert_summary_card_dispatched_active',
            'emergency_alert_summary_card_stable_active'
        );

        if (active) {
            card.classList.add('emergency_alert_summary_card_active');
            if (status === 'critical') card.classList.add('emergency_alert_summary_card_critical_active');
            if (status === 'dispatched') card.classList.add('emergency_alert_summary_card_dispatched_active');
            if (status === 'stable') card.classList.add('emergency_alert_summary_card_stable_active');
        }
    });
}

function emergencyAlertRender(status) {
    status = ['all', 'critical', 'dispatched', 'stable'].indexOf(status) >= 0 ? status : 'all';
    emergencyAlertSetActive(status);

    var notice = document.getElementById('emergency_alert_critical_notice');
    if (notice) notice.hidden = status !== 'critical';

    var data = window.emergency_alert_data;
    if (!data || !Array.isArray(data.alerts)) {
        return;
    }

    var input = document.getElementById('emergency_alert_search_input');
    var query = input ? input.value.trim().toLowerCase() : '';

    var alerts = data.alerts.filter(function (alert) {
        var statusMatch = status === 'all' || String(alert.status).toLowerCase() === status;
        if (!statusMatch) return false;

        if (!query) return true;

        var haystack = [
            alert.status,
            alert.incidentType,
            alert.patientInfo,
            alert.location,
            alert.responder,
            alert.eta,
            alert.timeAgo
        ].join(' ').toLowerCase();

        return haystack.indexOf(query) !== -1;
    });

    emergencyAlertRenderTable(status, alerts);
}

function emergencyAlertRenderTable(status, alerts) {
    var table = document.getElementById('emergency_alert_table');
    var head = document.getElementById('emergency_alert_table_head');
    var body = document.getElementById('emergency_alert_table_body');
    var empty = document.getElementById('emergency_alert_empty');

    if (!table || !head || !body || !empty) return;

    table.className = 'emergency_alert_table emergency_alert_table_' + status;
    table.setAttribute('data-view', status);

    if (status === 'all') {
        head.innerHTML = '<tr id="emergency_alert_table_head_row">' +
            '<th>STATUS</th><th>INCIDENT TYPE</th><th>LOCATION</th><th>TIME</th><th>ACTION</th>' +
            '</tr>';
    } else if (status === 'critical') {
        head.innerHTML = '<tr id="emergency_alert_table_head_row">' +
            '<th>INCIDENT TYPE</th><th>LOCATION</th><th>TIME</th><th>ACTION</th>' +
            '</tr>';
    } else {
        head.innerHTML = '<tr id="emergency_alert_table_head_row">' +
            '<th>INCIDENT TYPE</th><th>LOCATION</th><th>RESPONDER</th>' +
            '<th>' + (status === 'dispatched' ? 'ETA' : 'TIME') + '</th><th>STATUS</th>' +
            '</tr>';
    }

    var html = '';
    for (var i = 0; i < alerts.length; i++) {
        var alert = alerts[i];
        var incident = emergencyAlertEscapeHtml(alert.incidentType || 'Unknown Incident');
        var patient = alert.patientInfo
            ? '<small class="emergency_alert_patient_info">' + emergencyAlertEscapeHtml(alert.patientInfo) + '</small>'
            : '';
        var location = emergencyAlertEscapeHtml(alert.location || 'Unknown Location');
        var time = emergencyAlertEscapeHtml(alert.timeAgo || 'Just now');
        var alertStatus = String(alert.status || '').toLowerCase();

        html += '<tr class="emergency_alert_row" id="emergency_alert_row_' + alert.id + '" data-alert-id="' + alert.id + '" data-status="' + alertStatus + '">';

        if (status === 'all') {
            html += '<td><span class="emergency_alert_status emergency_alert_status_' + alertStatus + '">' + emergencyAlertEscapeHtml(alert.status) + '</span></td>' +
                '<td class="emergency_alert_incident_cell"><strong class="emergency_alert_incident">' + incident + '</strong>' + patient + '</td>' +
                '<td class="emergency_alert_location">' + location + '</td>' +
                '<td class="emergency_alert_time">' + time + '</td>' +
                '<td>' + emergencyAlertActionHtml(alert) + '</td>';
        } else if (status === 'critical') {
            html += '<td class="emergency_alert_incident_cell"><strong class="emergency_alert_incident">' + incident + '</strong>' + patient + '</td>' +
                '<td class="emergency_alert_location">' + location + '</td>' +
                '<td class="emergency_alert_time">' + time + '</td>' +
                '<td>' + emergencyAlertActionHtml(alert) + '</td>';
        } else if (status === 'dispatched') {
            html += '<td class="emergency_alert_incident_cell"><strong class="emergency_alert_incident">' + incident + '</strong></td>' +
                '<td class="emergency_alert_location">' + location + '</td>' +
                '<td class="emergency_alert_responder">' + emergencyAlertEscapeHtml(alert.responder || '—') + '</td>' +
                '<td class="emergency_alert_time">' + emergencyAlertEscapeHtml(alert.eta || '—') + '</td>' +
                '<td><span class="emergency_alert_dispatch_status"><span class="emergency_alert_status_dot"></span>En route</span></td>';
        } else {
            html += '<td class="emergency_alert_incident_cell"><strong class="emergency_alert_incident">' + incident + '</strong>' + patient + '</td>' +
                '<td class="emergency_alert_location">' + location + '</td>' +
                '<td class="emergency_alert_responder">' + emergencyAlertEscapeHtml(alert.responder || '—') + '</td>' +
                '<td class="emergency_alert_time">' + time + '</td>' +
                '<td><span class="emergency_alert_dispatch_status emergency_alert_dispatch_status_stable">MONITORED</span></td>';
        }

        html += '</tr>';
    }

    body.innerHTML = html;
    empty.hidden = alerts.length !== 0;
    emergencyAlertStaggerRows();
}

function emergencyAlertActionHtml(alert) {
    var status = String(alert.status || '').toLowerCase();
    var action = status === 'critical' ? 'notify' : 'details';
    var label = action === 'notify' ? 'NOTIFY STAFF' : 'VIEW DETAILS';

    return '<button type="button" class="emergency_alert_action emergency_alert_action_' + action + '" data-action="' + action + '" data-alert-id="' + Number(alert.id) + '">' +
        label + ' <span>&rarr;</span></button>';
}

function emergencyAlertStaggerRows() {
    var rows = document.querySelectorAll('#emergency_alert_table_body .emergency_alert_row');
    for (var i = 0; i < rows.length; i++) {
        rows[i].style.animationDelay = (i * 45) + 'ms';
    }
}

function emergencyAlertBindActions() {
    var body = document.getElementById('emergency_alert_table_body');
    if (!body || body.dataset.delegated === '1') return;
    body.dataset.delegated = '1';

    body.addEventListener('click', function (event) {
        var button = event.target.closest('.emergency_alert_action');
        if (!button) return;

        var action = button.getAttribute('data-action');
        var id = button.getAttribute('data-alert-id');
        if (!id) return;

        if (action === 'notify') {
            emergencyAlertNotifyStaff(id, button);
        } else if (action === 'details') {
            emergencyAlertLoadDetails(id);
        }
    });
}

function emergencyAlertNotifyStaff(id, button) {
    button.disabled = true;
    var originalText = button.innerHTML;
    button.innerHTML = 'NOTIFYING...';

    var formData = new FormData();
    formData.append('id', id);

    var url = new URL(emergencyAlertControllerUrl(), window.location.origin);
    url.searchParams.set('action', 'notify');

    fetch(url.toString(), {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(function (response) {
            return response.json().then(function (data) {
                return { ok: response.ok, data: data };
            });
        })
        .then(function (result) {
            if (!result.ok || !result.data.success) {
                throw new Error(result.data.message || 'Notification failed.');
            }

            button.innerHTML = 'STAFF NOTIFIED';
            button.classList.add('emergency_alert_action_notified');
            emergencyAlertShowToast(result.data.message || 'Staff notification sent successfully.');
        })
        .catch(function (error) {
            button.disabled = false;
            button.innerHTML = originalText;
            emergencyAlertShowToast(error.message);
        });
}

function emergencyAlertLoadDetails(id) {
    var url = new URL(emergencyAlertControllerUrl(), window.location.origin);
    url.searchParams.set('action', 'details');
    url.searchParams.set('id', id);

    fetch(url.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(function (response) {
            return response.json().then(function (data) {
                return { ok: response.ok, data: data };
            });
        })
        .then(function (result) {
            if (!result.ok || !result.data.success) {
                throw new Error(result.data.message || 'Unable to load alert details.');
            }
            emergencyAlertOpenDetails(result.data.alert);
        })
        .catch(function (error) {
            emergencyAlertShowToast(error.message);
        });
}

function emergencyAlertOpenDetails(alert) {
    var modal = document.getElementById('emergency_alert_details_modal');
    var body = document.getElementById('emergency_alert_modal_body');

    if (!modal || !body) return;

    var details = [
        ['Status', alert.status],
        ['Incident', alert.incidentType],
        ['Patient / Case', alert.patientInfo || '—'],
        ['Location', alert.location],
        ['Responder', alert.responder || '—'],
        ['ETA / Time', alert.eta || alert.timeAgo || '—']
    ];

    body.innerHTML = details.map(function (item) {
        return '<div class="emergency_alert_detail_item">' +
            '<span class="emergency_alert_detail_label">' + emergencyAlertEscapeHtml(item[0]) + '</span>' +
            '<span class="emergency_alert_detail_value">' + emergencyAlertEscapeHtml(item[1]) + '</span>' +
            '</div>';
    }).join('');

    modal.classList.add('emergency_alert_modal_open');
    modal.setAttribute('aria-hidden', 'false');
}

function emergencyAlertBindModal() {
    var modal = document.getElementById('emergency_alert_details_modal');
    var close = document.getElementById('emergency_alert_modal_close');
    var backdrop = document.getElementById('emergency_alert_modal_backdrop');

    if (!modal) return;
    if (close) close.addEventListener('click', emergencyAlertCloseModal);
    if (backdrop) backdrop.addEventListener('click', emergencyAlertCloseModal);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') emergencyAlertCloseModal();
    });
}

function emergencyAlertCloseModal() {
    var modal = document.getElementById('emergency_alert_details_modal');
    if (!modal) return;

    modal.classList.remove('emergency_alert_modal_open');
    modal.setAttribute('aria-hidden', 'true');
}

function emergencyAlertBindBell() {
    var bell = document.getElementById('emergency_alert_bell_btn');
    var dot = document.getElementById('emergency_alert_bell_dot');

    if (!bell) return;

    bell.addEventListener('click', function () {
        if (dot) dot.hidden = true;
        emergencyAlertShowToast('Notifications marked as viewed.');
    });
}

function emergencyAlertPulseBell() {
    var bell = document.getElementById('emergency_alert_bell_btn');
    if (!bell) return;

    bell.classList.remove('emergency_alert_bell_pulse');
    void bell.offsetWidth;
    bell.classList.add('emergency_alert_bell_pulse');

    window.setTimeout(function () {
        bell.classList.remove('emergency_alert_bell_pulse');
    }, 1500);
}

function emergencyAlertAnimateSummaryCards() {
    var cards = document.querySelectorAll('.emergency_alert_summary_card');
    cards.forEach(function (card, index) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(8px)';

        window.setTimeout(function () {
            card.style.transition = 'opacity .35s ease, transform .35s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 60);
    });
}

function emergencyAlertBindNavigation() {
    document.querySelectorAll('.emergency_alert_nav_item').forEach(function (link) {
        link.addEventListener('click', function () {
            this.style.transform = 'scale(.99)';
            var item = this;
            window.setTimeout(function () { item.style.transform = ''; }, 120);
        });
    });
}

function emergencyAlertShowToast(message) {
    var toast = document.getElementById('emergency_alert_toast');
    if (!toast) return;

    toast.textContent = message;
    toast.classList.add('emergency_alert_toast_visible');

    window.clearTimeout(emergencyAlertToastTimer);
    emergencyAlertToastTimer = window.setTimeout(function () {
        toast.classList.remove('emergency_alert_toast_visible');
    }, 2800);
}

function emergencyAlertEscapeHtml(value) {
    return String(value == null ? '' : value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Aliases for compatibility
window.emergency_alert_init = emergencyAlertInit;
window.emergencyAlertInit = emergencyAlertInit;