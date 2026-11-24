<div class="modal fade formModal" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content row">
            <div class="col-12 rightSide">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ __('create_account') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    {{-- <p class="text-muted small mb-3">{{ __('student') }} — {{ __('registration_form') }}</p> --}}
                    <form id="studentSignupForm" class="create-form" action="#" method="post" novalidate>
                        @csrf
                        <div class="schoolFormWrapper">
                            <div class="formWrapper">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="inputWrapper">
                                            <label for="signup_first_name">{{ __('first_name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="first_name" id="signup_first_name"
                                                placeholder="{{ __('enter_your_first_name') }}" required
                                                autocomplete="given-name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="inputWrapper">
                                            <label for="signup_last_name">{{ __('last_name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="last_name" id="signup_last_name"
                                                placeholder="{{ __('enter_your_last_name') }}" required
                                                autocomplete="family-name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="inputWrapper">
                                            <label for="signup_email">{{ __('email') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" name="email" id="signup_email"
                                                placeholder="{{ __('enter_your_email') }}" required
                                                autocomplete="email">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="inputWrapper">
                                            <label for="signup_mobile">{{ __('mobile') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                                                name="mobile" id="signup_mobile"
                                                placeholder="{{ __('enter_your_contact_number') }}" maxlength="16"
                                                required autocomplete="tel">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="inputWrapper">
                                            <label for="signup_gender">{{ __('gender') }} <span
                                                    class="text-muted">({{ __('optional') }})</span></label>
                                            <select name="gender" id="signup_gender" class="form-select rounded-lg">
                                                <option value="">{{ __('select') }}</option>
                                                <option value="male">{{ __('male') }}</option>
                                                <option value="female">{{ __('female') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="inputWrapper">
                                            <label for="signup_dob">{{ __('dob') }} <span
                                                    class="text-muted">({{ __('optional') }})</span></label>
                                            <input type="date" name="dob" id="signup_dob" class="form-control rounded-lg"
                                                autocomplete="bday">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="inputWrapper">
                                            <label for="signup_password">{{ __('password') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" name="password" id="signup_password"
                                                placeholder="{{ __('password') }}" required minlength="6"
                                                autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="inputWrapper">
                                            <label for="signup_password_confirmation">{{ __('confirm_password') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" name="password_confirmation"
                                                id="signup_password_confirmation"
                                                placeholder="{{ __('confirm_password') }}" required minlength="6"
                                                autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="col-12 modalfooter">
                                        <div class="inputWrapper"></div>
                                        <div>
                                            <button type="submit" class="commonBtn" id="studentSignupSubmit">
                                                {{ __('submit') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        var form = document.getElementById('studentSignupForm');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var pwd = document.getElementById('signup_password').value;
            var pwd2 = document.getElementById('signup_password_confirmation').value;
            if (pwd !== pwd2) {
                var mismatchMsg = @json(__('password_confirmation_mismatch'));
                if (typeof $ !== 'undefined' && $.toast) {
                    $.toast({
                        text: mismatchMsg,
                        showHideTransition: 'slide',
                        icon: 'error',
                        loaderBg: '#f2a654',
                        position: 'top-right'
                    });
                } else {
                    alert(mismatchMsg);
                }
                return;
            }

            var btn = document.getElementById('studentSignupSubmit');
            btn.disabled = true;

            var payload = {
                first_name: document.getElementById('signup_first_name').value.trim(),
                last_name: document.getElementById('signup_last_name').value.trim(),
                email: document.getElementById('signup_email').value.trim(),
                mobile: document.getElementById('signup_mobile').value.trim(),
                password: pwd,
            };
            var g = document.getElementById('signup_gender').value;
            if (g) {
                payload.gender = g;
            }
            var dob = document.getElementById('signup_dob').value;
            if (dob) {
                payload.dob = dob;
            }

            fetch('{{ url('/api/register') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                })
                .then(function(res) {
                    return res.json().then(function(data) {
                        return {
                            ok: res.ok,
                            data: data
                        };
                    });
                })
                .then(function(result) {
                    btn.disabled = false;
                    if (result.ok && result.data.success) {
                        if (typeof $ !== 'undefined' && $.toast) {
                            $.toast({
                                text: result.data.message || @json(__('Success')),
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f2a654',
                                position: 'top-right'
                            });
                        }
                        form.reset();
                        var el = document.getElementById('staticBackdrop');
                        if (el && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                            var inst = bootstrap.Modal.getInstance(el);
                            if (inst) {
                                inst.hide();
                            }
                        }
                    } else {
                        var msg = (result.data && result.data.message) ? result.data.message : @json(__('something_wrong_try_again'));
                        if (typeof $ !== 'undefined' && $.toast) {
                            $.toast({
                                text: msg,
                                showHideTransition: 'slide',
                                icon: 'error',
                                loaderBg: '#f2a654',
                                position: 'top-right'
                            });
                        } else {
                            alert(msg);
                        }
                    }
                })
                .catch(function() {
                    btn.disabled = false;
                    var errMsg = @json(__('something_wrong_try_again'));
                    if (typeof $ !== 'undefined' && $.toast) {
                        $.toast({
                            text: errMsg,
                            showHideTransition: 'slide',
                            icon: 'error',
                            loaderBg: '#f2a654',
                            position: 'top-right'
                        });
                    } else {
                        alert(errMsg);
                    }
                });
        });
    })();
</script>
