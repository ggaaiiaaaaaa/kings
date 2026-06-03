<?php
/* Template Name: Careers */
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once 'functions.php';
}
$page_title       = 'Careers | Kings Group Cooperative';
$page_description = 'Join Kings Group Cooperative and start your career with ethical staffing and elite opportunities in various industries.';

// JSON-LD: EmploymentAgency schema — signals this is a jobs/hiring page to Google
$page_schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'EmploymentAgency',
    '@id'         => 'https://kingsgroup.com.ph/careers/#employmentagency',
    'name'        => 'Kings Group Cooperative — Careers',
    'url'         => 'https://kingsgroup.com.ph/careers/',
    'description' => 'Join Kings Group Cooperative and start your career with ethical staffing and elite opportunities in various industries.',
    'parentOrganization' => [ '@id' => 'https://kingsgroup.com.ph/#organization' ],
    'areaServed'  => [ '@type' => 'Country', 'name' => 'Philippines' ],
    'breadcrumb'  => [
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            [ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home',    'item' => 'https://kingsgroup.com.ph/' ],
            [ '@type' => 'ListItem', 'position' => 2, 'name' => 'Careers', 'item' => 'https://kingsgroup.com.ph/careers/' ],
        ],
    ],
];

$page_hero_bg     = kg_get_field('careers_bg', 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=2000&q=80');

get_header();
?>

    <!-- Premium Hero -->
    <?php
    $careers_headline = kg_get_field('careers_headline', 'Build Your Future<br><span style="color:var(--neutral-yellow);">Own Your Career</span>');
    $careers_desc = kg_get_field('careers_desc', 'Join the Philippines\' leading worker-owned cooperative. Get profit-sharing, career coaching, and a network of 10,000+ professionals.');
    ?>
    <?php
    // Hero background: use ACF image or plain gradient fallback
    $careers_bg = kg_get_field('careers_bg', 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=2000&q=80');
    $careers_bg_style = !empty($careers_bg) ? "background-image: linear-gradient(rgba(10, 37, 64, 0.75), rgba(10, 37, 64, 0.85)), url('" . esc_url($careers_bg) . "');" : '';
    ?>
    <section class="page-hero" style="<?php echo $careers_bg_style; ?>">
        <div class="container text-center">
            <h1><?php echo wp_kses_post($careers_headline); ?></h1>
            <p><?php echo esc_html($careers_desc); ?></p>
            <div style="margin-top:2rem;animation:fadeUp 1s ease-out 0.5s both;"></div>
        </div>
    </section>

    <!-- Application Wizard -->
    <?php
    $careers_form_title = kg_get_field('careers_form_title', 'Fast-Track Application');
    $careers_form_desc = kg_get_field('careers_form_desc', 'No long forms — upload your CV and we\'ll match you to the right role.');
    ?>
    <section id="apply" style="padding:5rem 0;background:var(--bg-white);">
        <div class="container" style="max-width:720px;">
            <div style="text-align:center;margin-bottom:2.5rem;">
                <h2 class="section-title" style="margin-bottom:0.75rem;"><?php echo esc_html($careers_form_title); ?></h2>
                <p style="color:var(--text-muted);font-size:1.1rem;"><?php echo wp_kses_post($careers_form_desc); ?></p>
            </div>

            <!-- Progress Bar -->
            <div id="career-progress"
                style="display:flex;align-items:center;justify-content:center;gap:0;margin-bottom:3rem;">
                <div class="cprog-step active" data-step="1">
                    <div class="cprog-circle">1</div>
                    <span>Upload CV</span>
                </div>
                <div class="cprog-line active"></div>
                <div class="cprog-step" data-step="2">
                    <div class="cprog-circle">2</div>
                    <span>Your Info</span>
                </div>
            </div>

            <!-- Step 1: Upload -->
            <div id="step-1" class="career-step active">
                <div id="cv-dropzone"
                    style="border:2px dashed var(--border-color);padding:3rem 2rem;text-align:center;cursor:pointer;transition:var(--transition);background:var(--bg-light);"
                    onclick="document.getElementById('cv-upload').click()">
                    <div
                        style="width:72px;height:72px;margin:0 auto 1.25rem;background:rgba(0,208,156,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)"
                            stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                    </div>
                    <h3 style="color:var(--text-dark);font-size:1.2rem;margin-bottom:0.5rem;">Drag & drop your CV here
                    </h3>
                    <p style="color:var(--text-muted);font-size:0.9rem;">or <span
                            style="color:var(--sec-accent-green);font-weight:600;text-decoration:underline;">browse
                            files</span></p>
                    <p style="color:var(--text-light);font-size:0.8rem;margin-top:0.75rem;">PDF, DOCX — Max 5 MB</p>
                    <input type="file" id="cv-upload" style="display:none;" accept=".pdf,.doc,.docx">
                </div>
                <div id="file-info"
                    style="display:none;margin-top:1rem;padding:1rem 1.25rem;background:rgba(0,208,156,0.06);border:1px solid rgba(0,208,156,0.2);display:none;align-items:center;gap:0.75rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)"
                        stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                    <span id="file-name"
                        style="flex:1;font-weight:500;color:var(--text-dark);font-size:0.95rem;"></span>
                    <button type="button" onclick="removeFile()"
                        style="background:none;border:none;cursor:pointer;color:var(--accent-red);font-size:0.85rem;font-weight:600;">Remove</button>
                </div>
                <button type="button" id="btn-step1" class="btn btn-primary"
                    style="width:100%;margin-top:1.5rem;padding:1rem;font-size:1.05rem;opacity:0.5;pointer-events:none;"
                    onclick="goToStep(2)">Continue</button>
            </div>

            <!-- Step 2: Contact Info -->
            <div id="step-2" class="career-step">
                <div style="display:flex;flex-direction:column;gap:1.25rem;">
                    <div class="careers-name-row">
                        <input type="text" id="app-fname" placeholder="First Name" required
                            style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;"
                            onfocus="this.style.borderColor='var(--main-blue)'"
                            onblur="this.style.borderColor='var(--border-color)'">
                        <input type="text" id="app-lname" placeholder="Last Name" required
                            style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;"
                            onfocus="this.style.borderColor='var(--main-blue)'"
                            onblur="this.style.borderColor='var(--border-color)'">
                    </div>
                    <input type="email" id="app-email" placeholder="Email Address" required
                        style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;"
                        onfocus="this.style.borderColor='var(--main-blue)'"
                        onblur="this.style.borderColor='var(--border-color)'">
                    <input type="tel" id="app-phone" placeholder="Phone Number"
                        style="padding:0.95rem 1.1rem;border:2px solid var(--border-color);font-family:var(--font-body);font-size:0.95rem;width:100%;transition:var(--transition);outline:none;"
                        onfocus="this.style.borderColor='var(--main-blue)'"
                        onblur="this.style.borderColor='var(--border-color)'">
                    
                    <!-- Role input is hidden now, capturing context automatically from URL if present -->
                    <input type="hidden" id="app-role" value="<?php echo esc_attr($_GET['role'] ?? ''); ?>">
                </div>

                <!-- honeypot -->
                <div style="display:none;" aria-hidden="true">
                    <input type="text" id="kg_hp_careers" name="kg_hp_field" value="" tabindex="-1" autocomplete="off">
                </div>
                <div id="careers-error" style="display:none;background:#fef2f2;border:1px solid #fca5a5;padding:0.75rem 1rem;margin-bottom:0.75rem;border-radius:6px;">
                    <p style="margin:0;color:#991b1b;font-size:0.9rem;" id="careers-error-msg"></p>
                </div>
                <div style="display:flex;gap:1rem;margin-top:1.5rem;">
                    <button type="button" class="btn btn-outline" style="flex:1;padding:1rem;"
                        onclick="goToStep(1)">Back</button>
                    <button type="button" class="btn btn-primary"
                        style="flex:2;padding:1rem;background:var(--sec-accent-green);color:var(--main-blue);font-size:1.05rem;"
                        onclick="submitApplication()">Submit Application</button>
                </div>
            </div>

        </div>
    </section>

    <!-- Success Modal -->
    <div id="success-modal" class="career-modal-overlay">
        <div class="career-modal">
            <button class="career-modal-close" onclick="closeModal()">&times;</button>
            <div
                style="width:80px;height:80px;margin:0 auto 1.5rem;background:rgba(0,208,156,0.12);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--sec-accent-green)"
                    stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </div>
            <h3 style="font-size:1.6rem;margin-bottom:0.75rem;color:var(--text-dark);text-align:center;">Application
                Received!</h3>
            <p
                style="color:var(--text-muted);font-size:1.05rem;max-width:440px;margin:0 auto 1.25rem;text-align:center;">
                Thank you for applying. Our talent team will review your profile and reach out within 2–3 business days.
            </p>
            <div id="modal-buttons"
                style="display:flex;flex-direction:column;gap:0.75rem;align-items:center;margin-top:1.5rem;">
                <button class="btn btn-primary"
                    style="padding:0.8rem 2.5rem;background:var(--sec-accent-green);color:var(--main-blue);width:100%;max-width:300px;"
                    onclick="showReview()">Check Application</button>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-outline"
                    style="padding:0.8rem 2.5rem;width:100%;max-width:300px;">Back to Home</a>
            </div>

            <!-- Application Review Panel -->
            <div id="modal-review" style="display:none;margin-top:1.5rem;">
                <h4
                    style="font-size:1rem;color:var(--text-dark);margin-bottom:1rem;text-align:center;text-transform:uppercase;letter-spacing:0.5px;font-size:0.8rem;">
                    Application Summary</h4>
                <div
                    style="background:var(--bg-light);border:1px solid var(--border-color);padding:1.25rem 1.5rem;display:flex;flex-direction:column;gap:0.75rem;">
                    <div class="review-row"><span class="review-label">CV File</span><span id="rev-cv"
                            class="review-value">—</span></div>
                    <div class="review-row"><span class="review-label">Name</span><span id="rev-name"
                            class="review-value">—</span></div>
                    <div class="review-row"><span class="review-label">Email</span><span id="rev-email"
                            class="review-value">—</span></div>
                    <div class="review-row"><span class="review-label">Phone</span><span id="rev-phone"
                            class="review-value">—</span></div>
                    <div class="review-row"><span class="review-label">Preferred Role</span><span id="rev-role"
                            class="review-value">—</span></div>
                </div>
                <div style="display:flex;flex-direction:column;gap:0.75rem;align-items:center;margin-top:1.25rem;">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-outline"
                        style="padding:0.8rem 2.5rem;width:100%;max-width:300px;">Back to Home</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Careers Page JS -->
    <style>
        /* Progress Steps */
        .cprog-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.4rem;
            min-width: 80px;
        }

        .cprog-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--text-muted);
            background: var(--bg-white);
            transition: var(--transition);
        }

        .cprog-step span {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .cprog-step.active .cprog-circle {
            background: var(--main-blue);
            color: #fff;
            border-color: var(--main-blue);
        }

        .cprog-step.active span {
            color: var(--main-blue);
            font-weight: 600;
        }

        .cprog-step.done .cprog-circle {
            background: var(--sec-accent-green);
            color: #fff;
            border-color: var(--sec-accent-green);
        }

        .cprog-line {
            flex: 1;
            height: 2px;
            background: var(--border-color);
            max-width: 100px;
            margin: 0 0.5rem;
            margin-top: -1rem;
            transition: var(--transition);
        }

        .cprog-line.active {
            background: var(--main-blue);
        }

        .cprog-line.done {
            background: var(--sec-accent-green);
        }

        /* Career Steps */
        .career-step {
            display: none;
        }

        .career-step.active {
            display: block;
        }

        /* Drag hover */
        #cv-dropzone.drag-over {
            border-color: var(--sec-accent-green);
            background: rgba(0, 208, 156, 0.04);
        }

        /* Success Modal */
        .career-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10, 37, 64, 0.6);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .career-modal-overlay.visible {
            display: flex;
        }

        .career-modal {
            background: var(--bg-white);
            padding: 3rem 2.5rem;
            max-width: 480px;
            width: 90%;
            position: relative;
            box-shadow: var(--shadow-xl);
        }

        .career-modal-close {
            position: absolute;
            top: 1rem;
            right: 1.25rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-muted);
            cursor: pointer;
            transition: color 0.2s;
            line-height: 1;
        }

        .career-modal-close:hover {
            color: var(--text-dark);
        }

        .review-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .review-row:last-child {
            border-bottom: none;
        }

        .review-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .review-value {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-dark);
            text-align: right;
            max-width: 60%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>

    <script>
        // Multi-step wizard
        let currentStep = 1;

        function goToStep(step) {
            document.querySelectorAll('.career-step').forEach(s => s.classList.remove('active'));
            document.getElementById('step-' + step).classList.add('active');
            
            const steps = document.querySelectorAll('.cprog-step');
            const lines = document.querySelectorAll('.cprog-line');
            steps.forEach((s, i) => {
                s.classList.remove('active', 'done');
                if (i + 1 < step) s.classList.add('done');
                if (i + 1 === step) s.classList.add('active');
            });
            lines.forEach((l, i) => {
                l.classList.remove('active', 'done');
                if (i < step - 1) l.classList.add('done');
                if (i === step - 1) l.classList.add('active');
            });
            currentStep = step;
        }

        // File upload
        const cvInput = document.getElementById('cv-upload');
        const fileInfo = document.getElementById('file-info');
        const fileName = document.getElementById('file-name');
        const btnStep1 = document.getElementById('btn-step1');
        const dropzone = document.getElementById('cv-dropzone');

        cvInput.addEventListener('change', function () {
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
                fileInfo.style.display = 'flex';
                dropzone.style.display = 'none';
                btnStep1.style.opacity = '1';
                btnStep1.style.pointerEvents = 'auto';
            }
        });

        function removeFile() {
            cvInput.value = '';
            fileInfo.style.display = 'none';
            dropzone.style.display = 'block';
            btnStep1.style.opacity = '0.5';
            btnStep1.style.pointerEvents = 'none';
        }

        // Drag & drop
        dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('drag-over'); });
        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('drag-over'));
        dropzone.addEventListener('drop', e => {
            e.preventDefault();
            dropzone.classList.remove('drag-over');
            if (e.dataTransfer.files.length > 0) {
                cvInput.files = e.dataTransfer.files;
                cvInput.dispatchEvent(new Event('change'));
            }
        });

        // Submit
        function submitApplication() {
            const fname    = document.getElementById('app-fname').value.trim();
            const lname    = document.getElementById('app-lname').value.trim();
            const email    = document.getElementById('app-email').value.trim();
            const phone    = document.getElementById('app-phone').value.trim();
            const role     = document.getElementById('app-role').value;
            const cvFile   = cvInput.files[0];

            const errBox = document.getElementById('careers-error');
            const errMsg = document.getElementById('careers-error-msg');
            errBox.style.display = 'none';

            if (!fname || !lname || !email) {
                errMsg.textContent = 'Please fill in your first name, last name, and email.';
                errBox.style.display = 'block';
                return;
            }
            if (!cvFile) {
                errMsg.textContent = 'Please upload your CV before submitting.';
                errBox.style.display = 'block';
                return;
            }

            const submitBtn = document.querySelector('#step-2 .btn-primary');
            submitBtn.disabled    = true;
            submitBtn.textContent = 'Submitting…';

            const formData = new FormData();
            formData.append('action',       'kg_submit_application');
            formData.append('kg_nonce',     KG_AJAX.careers_nonce);
            formData.append('app_fname',    fname);
            formData.append('app_lname',    lname);
            formData.append('app_email',    email);
            formData.append('app_phone',    phone);
            formData.append('app_role',     role);
            formData.append('app_cv',       cvFile, cvFile.name);
            formData.append('kg_hp_field',  document.getElementById('kg_hp_careers').value);

            fetch(KG_AJAX.url, { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('rev-cv').textContent      = cvFile.name;
                        document.getElementById('rev-name').textContent    = fname + ' ' + lname;
                        document.getElementById('rev-email').textContent   = email;
                        document.getElementById('rev-phone').textContent   = phone || '—';
                        document.getElementById('rev-role').textContent    = role  || '—';

                        document.getElementById('modal-buttons').style.display = 'flex';
                        document.getElementById('modal-review').style.display  = 'none';
                        document.getElementById('success-modal').classList.add('visible');
                        document.body.style.overflow = 'hidden';
                    } else {
                        const errBox = document.getElementById('careers-error');
                        document.getElementById('careers-error-msg').textContent = (data.data && data.data.message) ? data.data.message : 'Submission failed. Please try again.';
                        errBox.style.display = 'block';
                    }
                })
                .catch(() => {
                    const errBox = document.getElementById('careers-error');
                    document.getElementById('careers-error-msg').textContent = 'Network error. Please try again.';
                    errBox.style.display = 'block';
                })
                .finally(() => {
                    submitBtn.disabled    = false;
                    submitBtn.textContent = 'Submit Application';
                });
        }

        // Show review panel
        function showReview() {
            document.getElementById('modal-buttons').style.display = 'none';
            document.getElementById('modal-review').style.display = 'block';
        }

        // Close modal
        function closeModal() {
            document.getElementById('success-modal').classList.remove('visible');
            document.body.style.overflow = '';
            goToStep(1);
            removeFile();
            document.querySelectorAll('#step-2 input').forEach(el => { el.value = ''; });
        }

        // Close on overlay click
        document.getElementById('success-modal').addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });
    </script>

<?php get_footer(); ?>
