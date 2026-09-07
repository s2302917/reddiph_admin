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