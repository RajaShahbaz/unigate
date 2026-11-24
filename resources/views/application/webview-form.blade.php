<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $university->name }} - Application Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f7fa;
            padding: 16px;
            padding-bottom: 100px;
        }

        .container {
            max-width: 640px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow-x: hidden;
            margin-bottom: 50px;
        }

        .header {
            background: rgba(70, 90, 40, 1) !important;
            color: white;
            padding: 24px;
            text-align: center;
        }

        .header h1 {
            font-size: 20px;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .progress-bar {
            background: #e0e7ff;
            height: 8px;
            border-radius: 4px;
            margin: 0 24px 16px;
            overflow: hidden;
        }

        .progress-fill {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            height: 100%;
            transition: width 0.3s ease;
        }

        .step-indicators {
            display: flex;
            justify-content: space-between;
            padding: 0 24px 24px;
            overflow-x: auto;
        }

        .step-indicator {
            flex: 1;
            text-align: center;
            padding: 8px 4px;
            min-width: 60px;
        }

        .step-indicator .circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e0e7ff;
            color: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 4px;
            font-weight: bold;
            font-size: 14px;
        }

        .step-indicator.active .circle {
            background: #667eea;
            color: white;
        }

        .step-indicator.completed .circle {
            background: #10b981;
            color: white;
        }

        .step-indicator .label {
            font-size: 11px;
            color: #64748b;
        }

        .step-content {
            padding: 24px;
            display: none;
        }

        .step-content.active {
            display: block;
        }

        .step-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .step-description {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 24px;
        }

        .highlight-banner {
            background: #fff7d6;
            border: 1px solid #facc15;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 24px;
            box-shadow: 0 8px 16px rgba(250, 204, 21, 0.2);
        }

        .highlight-banner strong {
            color: #854d0e;
            display: block;
            margin-bottom: 6px;
        }

        .highlight-pulse {
            animation: highlightPulse 1.6s ease-out infinite;
            border-color: #f97316 !important;
        }

        @keyframes highlightPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.35);
            }
            70% {
                box-shadow: 0 0 0 12px rgba(249, 115, 22, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(249, 115, 22, 0);
            }
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
        }

        label .required {
            color: #ef4444;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #1e293b;
            background: white;
            transition: border-color 0.2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .helper-text {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: block;
            padding: 12px;
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .file-input-label:hover {
            border-color: #667eea;
            background: #f8fafc;
        }

        .file-name {
            margin-top: 8px;
            font-size: 12px;
            color: #64748b;
        }

        .radio-group,
        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .radio-option,
        .checkbox-option {
            display: flex;
            align-items: center;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .radio-option:hover,
        .checkbox-option:hover {
            border-color: #667eea;
            background: #f8fafc;
        }

        .radio-option input,
        .checkbox-option input {
            margin-right: 12px;
            width: 18px;
            height: 18px;
        }

        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .form-navigation {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 16px;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 12px;
        }

        .btn {
            flex: 1;
            padding: 14px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: rgba(70, 90, 40, 1) !important;
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover:not(:disabled) {
            background: #e2e8f0;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 40px;
        }

        .loading.show {
            display: block;
        }

        .spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .debug-panel {
            position: fixed;
            bottom: 80px;
            right: 16px;
            background: #1e293b;
            color: #f1f5f9;
            padding: 12px;
            border-radius: 8px;
            max-width: 300px;
            max-height: 300px;
            overflow-y: auto;
            font-size: 11px;
            font-family: monospace;
            display: none;
            z-index: 9999;
        }

        .debug-panel.show {
            display: block;
        }

        .debug-toggle {
            position: fixed;
            bottom: 80px;
            left: 16px;
            background: #1e293b;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            z-index: 9999;
        }

        .skip-save-mode {
            position: fixed;
            top: 16px;
            right: 16px;
            background: #fbbf24;
            color: #78350f;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            display: none;
            z-index: 9999;
        }

        .summary-container {
            margin-top: 24px;
        }

        .summary-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        .summary-section-title {
            font-size: 16px;
            font-weight: 600;
            color: rgba(70, 90, 40, 1);
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid rgba(70, 90, 40, 1);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            font-weight: 500;
            color: #64748b;
            font-size: 14px;
            flex: 0 0 40%;
        }

        .summary-value {
            color: #1e293b;
            font-size: 14px;
            flex: 1;
            text-align: right;
            word-break: break-word;
        }

        .summary-value.empty {
            color: #94a3b8;
            font-style: italic;
        }

        .summary-file {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: #dcfce7;
            color: #166534;
            border-radius: 6px;
            font-size: 12px;
        }

        .summary-file.missing {
            background: #fee2e2;
            color: #991b1b;
        }

        .summary-loading {
            text-align: center;
            padding: 40px 20px;
        }

        .summary-loading p {
            margin-top: 16px;
            color: #64748b;
        }

        @media (max-width: 480px) {
            body {
                padding: 8px;
            }

            .header {
                padding: 16px;
            }

            .header h1 {
                font-size: 18px;
            }

            .step-content {
                padding: 16px;
            }

            .form-navigation {
                padding: 12px;
            }

            .btn {
                padding: 12px 16px;
                font-size: 14px;
            }

            .debug-panel {
                bottom: 70px;
                right: 8px;
                max-width: calc(100% - 16px);
            }

            .summary-item {
                flex-direction: column;
                gap: 4px;
            }

            .summary-label {
                flex: 1;
            }

            .summary-value {
                text-align: left;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ $university->name }}</h1>
            <p>Application Form</p>
        </div>

        <div class="progress-bar">
            <div class="progress-fill" id="progressFill" style="width: 0%"></div>
        </div>

        <div class="step-indicators" id="stepIndicators">
            @foreach ($steps as $index => $step)
                <div class="step-indicator {{ $index == 0 ? 'active' : '' }}" data-step="{{ $index + 1 }}">
                    <div class="circle">{{ $index + 1 }}</div>
                    <div class="label">
                        {{ $isCustom ? $step->step_title ?? 'Step ' . ($index + 1) : $step['title'] ?? 'Step ' . ($index + 1) }}
                    </div>
                </div>
            @endforeach
        </div>

        <div id="alertContainer"></div>

        @if(!empty($documentRequestBanner))
            <div class="highlight-banner">
                <strong>{{ $documentRequestBanner->field_label ?? 'Document needs attention' }}</strong>
                <div class="mb-2">{{ $documentRequestBanner->reason }}</div>
                @if($documentRequestBanner->created_at)
                    <small>Requested on {{ $documentRequestBanner->created_at->format('M d, Y H:i') }}</small>
                @endif
            </div>
        @endif

        <form id="applicationForm">
            @csrf
            <input type="hidden" name="university_id" value="{{ $university->id }}">
            <input type="hidden" id="existing_token" name="token" value="{{ $token }}">
            <input type="hidden" name="user_id" value="{{ $user ? $user->id : request()->user_id }}">



            <input type="hidden" name="application_id" value="{{ $application->id ?? '' }}" id="applicationId">

            @foreach ($steps as $index => $step)
            <input type="hidden" name="user_id" value="{{ $user ? $user->id : request()->user_id }}">
                @php
                    $isLastStep = ($index + 1) == count($steps);
                @endphp
                <div class="step-content {{ $index == 0 ? 'active' : '' }}" data-step="{{ $index + 1 }}">
                    <div class="step-title">
                        {{ $isCustom
    ? ($step->step_title ?? 'Step ' . ($index + 1))
    : ($step->title ?? 'Step ' . ($index + 1))
}}
                    </div>
                    @if (
                        ($isCustom && isset($step->step_description) && $step->step_description) ||
                            (!$isCustom && isset($step['description']) && $step['description']))
                        <div class="step-description">{{ $isCustom ? $step->step_description : $step['description'] }}
                        </div>
                    @endif

                    @if ($isLastStep)
                        {{-- Summary View for Last Step --}}
                        <div id="applicationSummary" class="summary-container">
                            <div class="summary-loading">
                                <div class="spinner"></div>
                                <p>Loading your application details...</p>
                            </div>
                        </div>

                        {{-- Package Purchase Section --}}
                        @if ($package && !$hasPackage)
                            <div id="packageSection" class="package-section" style="margin-top: 24px; padding: 20px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px;">
                                <h3 style="margin: 0 0 12px 0; color: #856404; font-size: 18px; font-weight: 600;">
                                    <i class="fas fa-shopping-cart" style="margin-right: 8px;"></i>Package Required
                                </h3>
                                <p style="margin: 0 0 16px 0; color: #856404; font-size: 14px;">
                                    You need to purchase a package before submitting your application.
                                </p>

                                <div class="package-card" style="background: white; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                        <h4 style="margin: 0; font-size: 16px; font-weight: 600; color: #333;">
                                            {{ $package->name }}
                                        </h4>
                                        <span style="font-size: 20px; font-weight: bold; color: #667eea;">
                                            {{ number_format($package->price, 2) }} {{ $package->currency }}
                                        </span>
                                    </div>
                                    @if($package->description)
                                        <p style="margin: 8px 0 0 0; color: #666; font-size: 14px;">
                                            {{ $package->description }}
                                        </p>
                                    @endif
                                </div>

                                <div style="display: flex; gap: 12px; margin-bottom: 12px;">
                                    <button type="button" id="jazzcashWalletBtn" onclick="showJazzCashModal('wallet')"
                                        style="flex: 1; padding: 14px; background: #f59e0b; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.3s;">
                                        <i class="fas fa-wallet" style="margin-right: 6px;"></i>JazzCash Wallet
                                    </button>
                                    {{-- <button type="button" id="jazzcashCardBtn" onclick="showJazzCashModal('card')"
                                        style="flex: 1; padding: 14px; background: #10b981; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.3s;">
                                        <i class="fas fa-credit-card" style="margin-right: 6px;"></i>Card Payment
                                    </button> --}}
                                </div>

                                <p id="packageMessage" style="margin: 12px 0 0 0; color: #856404; font-size: 13px; display: none;"></p>
                            </div>
                        @elseif ($package && $hasPackage)
                            <div class="package-section" style="margin-top: 24px; padding: 16px; background: #d4edda; border: 2px solid #28a745; border-radius: 8px;">
                                <h3 style="margin: 0 0 8px 0; color: #155724; font-size: 16px; font-weight: 600;">
                                    <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                                    Application Package Active
                                </h3>
                                <p style="margin: 0 0 6px 0; color: #166534; font-size: 14px;">
                                    You have an active package and can submit this application.
                                </p>
                                <p style="margin: 0; color: #166534; font-size: 13px;">
                                    <strong>Package:</strong> {{ $package->name }} -
                                    {{ number_format($package->price, 2) }} {{ $package->currency }}
                                    @if($userPackage && $userPackage->purchased_at)
                                        <br>
                                        <strong>Purchased on:</strong> {{ $userPackage->purchased_at->format('M d, Y H:i') }}
                                    @endif
                                </p>
                            </div>
                        @endif
                    @else
                        {{-- Regular Form Fields for Other Steps --}}
                        @php
                            $fields = $isCustom ? $step->activeFields : collect($step['fields']);
                        @endphp

                        @foreach ($fields as $field)
                        @php
                            $fieldName = $isCustom ? $field->field_name : $field['name'];
                            $fieldType = $isCustom ? $field->field_type : $field['type'];
                            $fieldLabel = $isCustom ? $field->field_label : $field['label'];
                            $isRequired = $isCustom ? $field->is_required : $field['required'];
                            $placeholder = $isCustom ? $field->field_placeholder : $field['placeholder'] ?? '';
                            $helperText = $isCustom ? $field->helper_text : $field['helper_text'] ?? '';

                            // Safely get field value and handle arrays
                            $rawValue = $userData[$fieldName] ?? '';
                            if (is_array($rawValue)) {
                                $fieldValue =
                                    $fieldType == 'checkbox' ? $rawValue : (count($rawValue) > 0 ? $rawValue[0] : '');
                            } else {
                                $fieldValue = $rawValue;
                            }
                        @endphp


                        <div class="form-group mb-5">
                            <label for="{{ $fieldName }}">
                                {{ is_array($fieldLabel) ? 'Field' : $fieldLabel }}
                                @if ($isRequired)
                                    <span class="required">*</span>
                                @endif
                            </label>

                            @if ($fieldType == 'text' || $fieldType == 'email' || $fieldType == 'tel' || $fieldType == 'number')
                                <input type="{{ $fieldType }}" id="{{ $fieldName }}" name="{{ $fieldName }}"
                                    placeholder="{{ is_array($placeholder) ? '' : $placeholder }}"
                                    value="{{ is_array($fieldValue) ? '' : $fieldValue }}"
                                    {{ $isRequired ? 'required' : '' }}>
                            @elseif($fieldType == 'date')
                                <input type="date" id="{{ $fieldName }}" name="{{ $fieldName }}"
                                    value="{{ is_array($fieldValue) ? '' : $fieldValue }}"
                                    {{ $isRequired ? 'required' : '' }}>
                            @elseif($fieldType == 'textarea')
                                <textarea id="{{ $fieldName }}" name="{{ $fieldName }}"
                                    placeholder="{{ is_array($placeholder) ? '' : $placeholder }}" {{ $isRequired ? 'required' : '' }}>{{ is_array($fieldValue) ? '' : $fieldValue }}</textarea>
                            @elseif($fieldType == 'select')
                                <select id="{{ $fieldName }}" name="{{ $fieldName }}"
                                    {{ $isRequired ? 'required' : '' }}>
                                    <option value="">Select {{ is_array($fieldLabel) ? 'Option' : $fieldLabel }}
                                    </option>
                                    @php
                                        $options = $isCustom ? $field->options_array ?? [] : $field['options'] ?? [];
                                        if (!is_array($options)) {
                                            $options = [];
                                        }
                                        if ($fieldName == 'interest_id') {
                                        }
                                    @endphp

                                    @foreach ($options as $option)
                                        @php
                                            // If option is array, extract value/label
                                            if (is_array($option)) {
                                                $value = $option['value'] ?? ($option['id'] ?? null);
                                                $label = $option['label'] ?? ($option['name'] ?? $value);
                                            } else {
                                                // If option is just a string
                                                $value = $option;
                                                $label = $option;
                                            }
                                        @endphp
                                        <option value="{{ $value }}"
                                            {{ $fieldValue == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            @elseif($fieldType == 'radio')
                                <div class="radio-group">
                                    @php
                                        $options = $isCustom ? $field->options_array ?? [] : $field['options'] ?? [];
                                        if (!is_array($options)) {
                                            $options = [];
                                        }
                                    @endphp
                                    @foreach ($options as $option)
                                        <label class="radio-option">
                                            <input type="radio" name="{{ $fieldName }}"
                                                value="{{ $option }}"
                                                {{ !is_array($fieldValue) && $fieldValue == $option ? 'checked' : '' }}
                                                {{ $isRequired ? 'required' : '' }}>
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($fieldType == 'checkbox')
                                <div class="checkbox-group">
                                    @php
                                        $options = $isCustom ? $field->options_array ?? [] : $field['options'] ?? [];
                                        if (!is_array($options)) {
                                            $options = [];
                                        }
                                        $selectedValues = is_array($fieldValue) ? $fieldValue : [];
                                    @endphp
                                    @foreach ($options as $option)
                                        <label class="checkbox-option">
                                            <input type="checkbox" name="{{ $fieldName }}[]"
                                                value="{{ $option }}"
                                                {{ in_array($option, $selectedValues) ? 'checked' : '' }}>
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($fieldType == 'file')
                                <div class="file-input-wrapper">
                                    <input type="file" id="{{ $fieldName }}" name="{{ $fieldName }}"
                                        {{ $isRequired ? 'required' : '' }} onchange="updateFileName(this)">
                                    <label for="{{ $fieldName }}" class="file-input-label">
                                        <span>📁 Choose File</span>
                                    </label>
                                    <div class="file-name" id="{{ $fieldName }}_name">
                                        @if ($fieldValue && !is_array($fieldValue))
                                            File uploaded
                                        @else
                                            No file chosen
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if ($helperText && !is_array($helperText))
                                <div class="helper-text">{{ $helperText }}</div>
                            @endif
                            <div class="error-message" id="{{ $fieldName }}_error"></div>
                        </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        </form>


        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p style="margin-top: 16px; color: #64748b;">Saving...</p>
        </div>
    </div>

    {{-- <button class="debug-toggle" onclick="toggleDebug()">🐛 Debug</button> --}}
    <div class="debug-panel" id="debugPanel"></div>
    <div class="skip-save-mode" id="skipSaveMode">⚠️ Test Mode: Skipping Save</div>

    <div class="form-navigation">
        <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;" onclick="prevStep()">
            ← Previous
        </button>
        <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextStep()">
            Next →
        </button>
        <button type="button" class="btn btn-primary" id="submitBtn" style="display: none;"
            onclick="submitForm()">
            Submit Application
        </button>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = {{ count($steps) }};
        const apiBaseUrl = '{{ url('/api') }}';

        // Get auth token from request or user
        // let authToken = document.getElementById('existing_token').value;
        let authToken = '4|n5PNvYeAUbNZNuMjTgo0fLdGI6XjqylQSJcai3ggc7aec343';
        // let authToken = '27|LDCdohWzgHEb2vPZCvcwc4thTYoRdeR3Kco2n7cU5360cf1d';

        @if (request()->bearerToken())
            //  authToken = '{{ request()->bearerToken() }}';
        @elseif (auth()->check() && auth()->user()->tokens()->count() > 0)
            //    authToken = '{{ auth()->user()->tokens()->first()->token }}';
        @endif

        let applicationId = document.getElementById('applicationId').value;

        // Debug mode - can be enabled via URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const debugMode = urlParams.has('debug');
        const skipSave = urlParams.has('skip_save');
        const highlightField = @json($highlightField ?? null);
        const highlightStep = @json($highlightStep ?? null);

        if (skipSave) {
            document.getElementById('skipSaveMode').style.display = 'block';
        }

        console.log('Form initialized');
        console.log('API Base URL:', apiBaseUrl);
        console.log('Auth Token:', authToken ? 'Present (length: ' + authToken.length + ')' : 'MISSING');
        console.log('University ID:', {{ $university->id }});
        console.log('Application ID:', applicationId || 'Not set yet');
        console.log('Debug mode:', debugMode ? 'ON' : 'OFF');
        console.log('Skip save mode:', skipSave ? 'ON' : 'OFF');

        // Log to debug panel
        function debugLog(message) {
            const debugPanel = document.getElementById('debugPanel');
            const time = new Date().toLocaleTimeString();
            debugPanel.innerHTML += `[${time}] ${message}<br>`;
            debugPanel.scrollTop = debugPanel.scrollHeight;
        }

        function toggleDebug() {
            const debugPanel = document.getElementById('debugPanel');
            debugPanel.classList.toggle('show');
        }

        debugLog('Form initialized');
        debugLog('Total steps: ' + totalSteps);
        debugLog('Auth token: ' + (authToken ? 'Present' : 'MISSING'));

        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {
            let initialStep = currentStep;
            if (highlightStep && !isNaN(parseInt(highlightStep, 10))) {
                const stepNum = parseInt(highlightStep, 10);
                if (stepNum >= 1 && stepNum <= totalSteps) {
                    initialStep = stepNum;
                    console.log('Navigating to step from URL:', stepNum);
                    debugLog('→ Navigating to step ' + stepNum + ' (from notification)');
                }
            }

            showStep(initialStep);

            // If no application ID, try to create one on load
            if (!applicationId) {
                console.log('No application ID on load. Will create when user clicks Next.');
                debugLog('⚠️ No app ID - will create on first Next');
            } else {
                console.log('Existing application ID found:', applicationId);
                debugLog('✓ Existing app ID: ' + applicationId);
            }

            // Focus on highlighted field after a short delay to ensure DOM is ready
            setTimeout(() => {
                focusOnHighlightedField();
            }, 100);

            // Load summary if on last step
            if (currentStep === totalSteps && applicationId) {
                loadApplicationSummary();
            }
        });

        async function startApplication() {
            console.log('Starting application for university:', {{ $university->id }});
            debugLog('Creating application...');
            console.log('Auth token available:', authToken ? 'Yes' : 'No');

            if (!authToken) {
                console.error('Cannot start application: No auth token');
                debugLog('✗ ERROR: No auth token');
                showAlert('Authentication error. Please refresh the page.', 'error');
                return false;
            }

            try {
                const response = await fetch(`${apiBaseUrl}/multi-step-application/start`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        university_id: {{ $university->id }},
                        user_id: {{ $user->id }}

                    })
                });

                console.log('Start application response status:', response.status);
                debugLog('HTTP Status: ' + response.status);

                const data = await response.json();
                console.log('Start application response data:', data);

                if (!data.error && data.data && data.data.application) {
                    applicationId = data.data.application.id;
                    document.getElementById('applicationId').value = applicationId;
                    console.log('Application ID set to:', applicationId);
                    debugLog('✓ App ID: ' + applicationId);
                    return true;
                } else {
                    console.error('Failed to start application:', data);
                    debugLog('✗ Create FAILED: ' + (data.message || 'Unknown error'));
                    showAlert(data.message || 'Failed to start application', 'error');
                    return false;
                }
            } catch (error) {
                console.error('Error starting application:', error);
                debugLog('✗ Network ERROR: ' + error.message);
                showAlert('Failed to create application. Please check your connection.', 'error');
                return false;
            }
        }

        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alertContainer.innerHTML = '';
            alertContainer.appendChild(alert);

            setTimeout(() => {
                alert.remove();
            }, 5000);
        }

        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : 'No file chosen';
            document.getElementById(input.id + '_name').textContent = fileName;
        }

        function updateProgress() {
            const progress = (currentStep / totalSteps) * 100;
            document.getElementById('progressFill').style.width = progress + '%';

            // Update step indicators
            document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                indicator.classList.remove('active', 'completed');
                if (index + 1 < currentStep) {
                    indicator.classList.add('completed');
                } else if (index + 1 === currentStep) {
                    indicator.classList.add('active');
                }
            });

            // Update navigation buttons
            document.getElementById('prevBtn').style.display = currentStep > 1 ? 'block' : 'none';
            document.getElementById('nextBtn').style.display = currentStep < totalSteps ? 'block' : 'none';
            document.getElementById('submitBtn').style.display = currentStep === totalSteps ? 'block' : 'none';
        }

        function showStep(step) {
            document.querySelectorAll('.step-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelector(`.step-content[data-step="${step}"]`).classList.add('active');
            currentStep = step;
            updateProgress();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // Load summary if we're on the last step
            if (step === totalSteps && applicationId) {
                loadApplicationSummary();
            }
        }

        function focusOnHighlightedField() {
            if (!highlightField) {
                return;
            }

            const targetField = document.querySelector(`[name="${highlightField}"]`) || document.getElementById(highlightField);
            const desiredStep = highlightStep ? parseInt(highlightStep, 10) : null;

            if (desiredStep && !isNaN(desiredStep) && desiredStep !== currentStep) {
                showStep(desiredStep);
            } else if (targetField) {
                const stepContainer = targetField.closest('.step-content');
                if (stepContainer) {
                    const stepFromDom = parseInt(stepContainer.dataset.step, 10);
                    if (!isNaN(stepFromDom) && stepFromDom !== currentStep) {
                        showStep(stepFromDom);
                    }
                }
            }

            if (targetField) {
                setTimeout(() => {
                    targetField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    targetField.classList.add('highlight-pulse');
                    setTimeout(() => targetField.classList.remove('highlight-pulse'), 6000);
                }, 500);
            }
        }

        async function saveCurrentStep() {
            console.log('Saving step:', currentStep);
            debugLog('--- Saving Step ' + currentStep + ' ---');
            console.log('Application ID:', applicationId);
            console.log('Auth token:', authToken ? 'Present' : 'Missing');

            const currentStepContent = document.querySelector(`.step-content[data-step="${currentStep}"]`);
            const formData = new FormData();
            let fieldCount = 0;

            // Get all inputs from current step
            currentStepContent.querySelectorAll('input, select, textarea').forEach(input => {
                if (input.type === 'checkbox') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                        fieldCount++;
                        console.log('Added checkbox:', input.name, '=', input.value);
                        debugLog('✓ ' + input.name + ': ' + input.value);
                    }
                } else if (input.type === 'radio') {
                    if (input.checked) {
                        formData.append(input.name, input.value);
                        fieldCount++;
                        console.log('Added radio:', input.name, '=', input.value);
                        debugLog('✓ ' + input.name + ': ' + input.value);
                    }
                } else if (input.type === 'file') {
                    if (input.files[0]) {
                        formData.append(input.name, input.files[0]);
                        fieldCount++;
                        console.log('Added file:', input.name);
                        debugLog('✓ ' + input.name + ': FILE');
                    }
                } else if (input.value && input.name && input.name !== '_token') {
                    formData.append(input.name, input.value);
                    fieldCount++;
                    console.log('Added field:', input.name, '=', input.value);
                    debugLog('✓ ' + input.name + ': ' + input.value);
                }
            });

            console.log('Total fields to save:', fieldCount);
            debugLog('Total fields: ' + fieldCount);

            if (fieldCount === 0) {
                console.warn('No fields to save in this step');
                debugLog('⚠️ No fields to save');
                // If no fields, just proceed to next step
                return true;
            }

            document.getElementById('loading').classList.add('show');

            const url = `${apiBaseUrl}/multi-step-application/${applicationId}/step/${currentStep}`;
            console.log('Saving to URL:', url);
            debugLog('URL: ' + url);

            try {
                formData.append('user_id', {{ $user->id }});
                formData.append('university_id', {{ $university->id }});

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                console.log('Response status:', response.status);
                debugLog('HTTP Status: ' + response.status);

                const data = await response.json();
                console.log('Response data:', data);

                if (!data.error && response.ok) {
                    debugLog('✓ Save SUCCESS');
                    showAlert('Step saved successfully!', 'success');
                    return true;
                } else {
                    const errorMessage = data.message || 'Error saving step';
                    console.error('Save failed:', errorMessage, data);
                    debugLog('✗ Save FAILED: ' + errorMessage);
                    showAlert(errorMessage, 'error');

                    // Show validation errors if present
                    if (data.data && typeof data.data === 'object') {
                        Object.keys(data.data).forEach(field => {
                            const errors = Array.isArray(data.data[field]) ? data.data[field].join(', ') : data
                                .data[field];
                            console.error(`Validation error for ${field}:`, errors);
                            debugLog(`✗ ${field}: ${errors}`);
                        });
                    }

                    return false;
                }
            } catch (error) {
                console.error('Network error saving step:', error);
                debugLog('✗ Network ERROR: ' + error.message);
                showAlert('Network error. Please check your connection and try again.', 'error');
                return false;
            } finally {
                document.getElementById('loading').classList.remove('show');
            }
        }

        async function nextStep() {
            console.log('====== NEXT BUTTON CLICKED ======');
            debugLog('Next button clicked');
            console.log('Current step:', currentStep);
            debugLog('Current step: ' + currentStep);

            // Check if application ID exists
            if (!applicationId) {
                console.log('No application ID. Creating application...');
                debugLog('Creating application...');

                // Show loading
                document.getElementById('loading').classList.add('show');

                const created = await startApplication();

                document.getElementById('loading').classList.remove('show');

                if (!applicationId || !created) {
                    console.error('Failed to create application');
                    debugLog('ERROR: Failed to create application');
                    showAlert('Failed to create application. Please refresh the page.', 'error');
                    return;
                }

                console.log('Application created successfully. ID:', applicationId);
                debugLog('✓ Application created: ' + applicationId);
                showAlert('Application created! Now saving your information...', 'success');
            }

            // Validate current step
            const currentStepContent = document.querySelector(`.step-content[data-step="${currentStep}"]`);
            if (!currentStepContent) {
                console.error('Could not find step content for step:', currentStep);
                debugLog('ERROR: Step content not found');
                showAlert('Error: Step content not found', 'error');
                return;
            }

            const requiredInputs = currentStepContent.querySelectorAll('[required]');
            console.log('Required inputs found:', requiredInputs.length);
            debugLog('Required inputs: ' + requiredInputs.length);

            let isValid = true;
            let missingFields = [];

            requiredInputs.forEach(input => {
                const inputValue = input.value;
                const inputName = input.name;

                if (!inputValue || (input.type === 'radio' && !currentStepContent.querySelector(
                        `input[name="${inputName}"]:checked`))) {
                    input.style.borderColor = '#ef4444';
                    isValid = false;
                    missingFields.push(inputName);
                    console.log('Missing required field:', inputName);
                } else {
                    input.style.borderColor = '#e2e8f0';
                }
            });

            if (!isValid) {
                console.log('Validation failed. Missing fields:', missingFields);
                debugLog('Validation FAILED: ' + missingFields.join(', '));
                showAlert('Please fill in all required fields: ' + missingFields.join(', '), 'error');
                return;
            }

            console.log('Validation passed ✓');
            debugLog('Validation PASSED ✓');

            // Skip save mode for testing
            if (skipSave) {
                console.log('Skipping save (test mode). Moving to next step...');
                debugLog('SKIPPING SAVE (test mode)');
                if (currentStep < totalSteps) {
                    showStep(currentStep + 1);
                }
                return;
            }

            console.log('Saving current step...');
            debugLog('Saving step ' + currentStep + '...');

            // Save current step
            const saved = await saveCurrentStep();
            console.log('Save result:', saved);
            debugLog('Save result: ' + (saved ? 'SUCCESS ✓' : 'FAILED ✗'));

            if (!saved) {
                console.error('Failed to save step');
                debugLog('ERROR: Save failed');
                return;
            }

            // Move to next step
            if (currentStep < totalSteps) {
                console.log('Moving to step:', currentStep + 1);
                debugLog('Moving to step: ' + (currentStep + 1));
                showStep(currentStep + 1);
            } else {
                console.log('Already on last step');
                debugLog('Already on last step');
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        }

        async function submitForm() {
            // Check if package is required and purchased (using data passed with the form)
            const hasPackage = @json($hasPackage ?? false);
            const package = @json($package ?? null);

            if (package && !hasPackage) {
                showAlert('Please purchase a package before submitting your application.', 'error');
                const packageSection = document.getElementById('packageSection');
                if (packageSection) {
                    packageSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }

            // Save final step first
            const saved = await saveCurrentStep();
            if (!saved) return;

            if (!confirm(
                    'Are you sure you want to submit this application? You will not be able to edit it after submission.'
                )) {
                return;
            }

            document.getElementById('loading').classList.add('show');
            const userId = "{{ request()->user_id }}";

            try {
                const response = await fetch(`${apiBaseUrl}/multi-step-application/${applicationId}/submit?user_id=${userId}`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                });

                const data = await response.json();

                if (!data.error) {
                    showAlert('Application submitted successfully!', 'success');
                    setTimeout(() => {
                        // Notify Flutter app
                        if (window.FlutterChannel) {
                            window.FlutterChannel.postMessage('application_submitted');
                        }
                        // Or use alternative method
                        // window.location.href = 'app://application-submitted';
                    }, 2000);
                } else {
                    showAlert(data.message || 'Error submitting application', 'error');
                }
            } catch (error) {
                console.error('Error submitting application:', error);
                showAlert('Network error. Please try again.', 'error');
            } finally {
                document.getElementById('loading').classList.remove('show');
            }
        }

        async function purchasePackage() {
            const package = @json($package ?? null);
            const userId = {{ $user ? $user->id : 'null' }};
            const applicationId = document.getElementById('applicationId').value;

            if (!package || !userId || !applicationId) {
                showAlert('Missing required information. Please refresh the page.', 'error');
                return;
            }

            const purchaseBtn = document.getElementById('purchasePackageBtn');
            const packageMessage = document.getElementById('packageMessage');

            // Disable button
            purchaseBtn.disabled = true;
            purchaseBtn.textContent = 'Processing...';
            packageMessage.style.display = 'none';

            try {
                // Create a Stripe Checkout session via API
                const response = await fetch(`${apiBaseUrl}/application-packages/purchase`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        package_id: package.id,
                        application_id: applicationId,
                        user_id: userId
                    })
                });

                const data = await response.json();

                if (data.error) {
                    showAlert(data.message || 'Failed to start payment. Please try again.', 'error');
                    purchaseBtn.disabled = false;
                    purchaseBtn.textContent = `Purchase Package - ${package.price} ${package.currency}`;
                    return;
                }

                const payload = data.data || {};

                if (payload.already_purchased || payload.has_package) {
                    packageMessage.textContent = 'You have already purchased this package. You can submit your application.';
                    packageMessage.style.color = '#155724';
                    packageMessage.style.display = 'block';
                    purchaseBtn.disabled = true;
                    purchaseBtn.textContent = 'Package Purchased';
                    return;
                }

                if (payload.checkout_url) {
                    packageMessage.textContent = 'Redirecting to secure payment page...';
                    packageMessage.style.color = '#155724';
                    packageMessage.style.display = 'block';

                    // Open Stripe Checkout in a new tab/window
                    window.open(payload.checkout_url, '_blank');

                    // Re-enable button so user can try again if needed
                    purchaseBtn.disabled = false;
                    purchaseBtn.textContent = `Purchase Package - ${package.price} ${package.currency}`;
                } else {
                    showAlert('Unexpected response from payment server. Please contact support.', 'error');
                    purchaseBtn.disabled = false;
                    purchaseBtn.textContent = `Purchase Package - ${package.price} ${package.currency}`;
                }
            } catch (error) {
                console.error('Error purchasing package:', error);
                showAlert('Network error. Please try again.', 'error');
                purchaseBtn.disabled = false;
                purchaseBtn.textContent = `Purchase Package - ${package.price} ${package.currency}`;
            }
        }

        // Add input validation on change
        document.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
            input.addEventListener('change', function() {
                if (this.value) {
                    this.style.borderColor = '#e2e8f0';
                }
            });
        });

        // Load and display application summary
        async function loadApplicationSummary() {
            if (!applicationId) {
                console.log('No application ID, cannot load summary');
                return;
            }

            const summaryContainer = document.getElementById('applicationSummary');
            if (!summaryContainer) {
                return;
            }

            console.log('Loading application summary for ID:', applicationId);
            debugLog('Loading summary...');

            try {
                const userId = {{ $user ? $user->id : 'null' }};
                const url = `${apiBaseUrl}/multi-step-application/${applicationId}/summary${userId ? '?user_id=' + userId : ''}`;

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                console.log('Summary response:', data);

                if (!data.error && data.data && data.data.application) {
                    renderSummary(data.data.application);
                    debugLog('✓ Summary loaded');
                } else {
                    // Fallback: build from existing data
                    console.warn('API summary failed, building from existing data');
                    renderSummaryFromExistingData();
                }
            } catch (error) {
                console.error('Error loading summary:', error);
                // Fallback: build from existing data
                renderSummaryFromExistingData();
            }
        }

        function renderSummary(application) {
            const summaryContainer = document.getElementById('applicationSummary');
            if (!summaryContainer) {
                return;
            }

            renderSummaryFromData(application);
        }

        function renderSummaryFromExistingData() {
            const summaryContainer = document.getElementById('applicationSummary');
            if (!summaryContainer) {
                return;
            }

            // Use data already in the view
            const userData = @json($userData ?? []);
            renderSummaryFromData(userData);
        }

        function renderSummaryFromData(applicationData) {
            const summaryContainer = document.getElementById('applicationSummary');
            if (!summaryContainer) {
                return;
            }

            let html = '';

            // Get all steps data to build comprehensive summary
            const allSteps = @json($steps);
            const isCustom = @json($isCustom);

            // Helpers to safely extract values
            const normalizeArray = (maybeArray) => {
                if (Array.isArray(maybeArray)) {
                    return maybeArray;
                }
                if (maybeArray && typeof maybeArray === 'object') {
                    return Object.values(maybeArray);
                }
                return [];
            };

            const getter = (item, camel, snake, fallback = '') => {
                if (!item) return fallback;
                if (Object.prototype.hasOwnProperty.call(item, camel)) {
                    return item[camel];
                }
                if (Object.prototype.hasOwnProperty.call(item, snake)) {
                    return item[snake];
                }
                return fallback;
            };

            const summarySections = {};

            allSteps.forEach((step, stepIndex) => {
                if (stepIndex + 1 === totalSteps) {
                    return;
                }

                const stepTitle = isCustom
                    ? (step.step_title ?? step.stepTitle ?? `Step ${stepIndex + 1}`)
                    : (step.title ?? `Step ${stepIndex + 1}`);

                const rawFields = isCustom
                    ? (step.activeFields ?? step.active_fields ?? [])
                    : (step.fields ?? []);

                const fields = normalizeArray(rawFields);

                if (fields.length === 0) {
                    return;
                }

                summarySections[stepTitle] = [];

                fields.forEach(field => {
                    const fieldName = getter(field, 'field_name', 'field_name', getter(field, 'name', 'name', ''));
                    if (!fieldName) {
                        return;
                    }
                    const fieldLabel = getter(field, 'field_label', 'field_label', getter(field, 'label', 'label', fieldName));
                    const fieldType = getter(field, 'field_type', 'field_type', getter(field, 'type', 'type', 'text'));

                    let value = applicationData[fieldName] || '';

                    if (fieldType === 'file') {
                        value = value ? '✓ Uploaded' : '✗ Not uploaded';
                    } else if (fieldType === 'select' && value) {
                        const rawOptions = getter(field, 'options_array', 'options_array', field.options || field.options_array || []);
                        const options = normalizeArray(rawOptions);
                        const option = options.find(opt => {
                            if (typeof opt === 'object') {
                                return (opt.value || opt.id) == value;
                            }
                            return opt == value;
                        });
                        if (option && typeof option === 'object') {
                            value = option.label || option.name || value;
                        } else if (option) {
                            value = option;
                        }
                    } else if (fieldType === 'checkbox') {
                        if (Array.isArray(value)) {
                            value = value.length > 0 ? value.join(', ') : 'Not provided';
                        } else if (typeof value === 'string') {
                            try {
                                const parsed = JSON.parse(value);
                                if (Array.isArray(parsed)) {
                                    value = parsed.length > 0 ? parsed.join(', ') : 'Not provided';
                                } else {
                                    value = value || 'Not provided';
                                }
                            } catch (e) {
                                value = value || 'Not provided';
                            }
                        } else {
                            value = value || 'Not provided';
                        }
                    } else if (fieldType === 'date' && value) {
                        // Format date
                        const date = new Date(value);
                        if (!isNaN(date.getTime())) {
                            value = date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                        }
                    }

                    if (value === '' || value === null || value === undefined) {
                        value = 'Not provided';
                    }

                    summarySections[stepTitle].push({
                        label: fieldLabel || fieldName,
                        value: value
                    });
                });
            });

            // Render summary sections
            Object.keys(summarySections).forEach(sectionTitle => {
                const items = summarySections[sectionTitle];
                if (items.length === 0) {
                    return;
                }

                html += `<div class="summary-section">`;
                html += `<div class="summary-section-title">${sectionTitle}</div>`;

                items.forEach(item => {
                    const isEmpty = item.value === 'Not provided' || item.value === '';
                    const isFile = item.value === '✓ Uploaded' || item.value === '✗ Not uploaded';
                    html += `<div class="summary-item">`;
                    html += `<div class="summary-label">${item.label}</div>`;
                    if (isFile) {
                        const fileClass = item.value === '✓ Uploaded' ? 'summary-file' : 'summary-file missing';
                        html += `<div class="${fileClass}">${item.value}</div>`;
                    } else {
                        html += `<div class="summary-value ${isEmpty ? 'empty' : ''}">${item.value}</div>`;
                    }
                    html += `</div>`;
                });

                html += `</div>`;
            });

            if (html === '') {
                html = '<div class="alert alert-error">No data available to display. Please complete the previous steps.</div>';
            }

            summaryContainer.innerHTML = html;
        }

        // JazzCash Payment Modal
        function showJazzCashModal(paymentType) {
            const package = @json($package ?? null);
            const userId = {{ $user ? $user->id : 'null' }};

            if (!package || !userId) {
                showAlert('Missing required information. Please refresh the page.', 'error');
                return;
            }

            // Create modal if it doesn't exist
            let modal = document.getElementById('jazzcashModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'jazzcashModal';
                modal.style.cssText = 'display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;';
                modal.innerHTML = `
                    <div style="background: white; padding: 24px; border-radius: 12px; max-width: 400px; width: 90%; max-height: 90vh; overflow-y: auto;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h3 style="margin: 0; font-size: 20px; font-weight: 600;">JazzCash Payment</h3>
                            <button onclick="closeJazzCashModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
                        </div>
                        <div id="jazzcashModalContent">
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500;">CNIC (without dashes)</label>
                                <input type="text" id="jazzcashCNIC" placeholder="1234567890123" maxlength="15"
                                    style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
                            </div>
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Mobile Number</label>
                                <input type="tel" id="jazzcashMobile" placeholder="03001234567" maxlength="15"
                                    style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
                            </div>
                            <button id="jazzcashSubmitBtn" onclick="processJazzCashPayment()"
                                style="width: 100%; padding: 14px; background: #f59e0b; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;">
                                Proceed with Payment
                            </button>
                            <p id="jazzcashMessage" style="margin: 12px 0 0 0; font-size: 13px; display: none;"></p>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
            }

            // Store payment type
            modal.setAttribute('data-payment-type', paymentType);

            // Update button text based on payment type
            const submitBtn = document.getElementById('jazzcashSubmitBtn');
            if (paymentType === 'wallet') {
                submitBtn.style.background = '#f59e0b';
                submitBtn.innerHTML = '<i class="fas fa-wallet" style="margin-right: 8px;"></i>Pay with JazzCash Wallet';
            } else {
                submitBtn.style.background = '#10b981';
                submitBtn.innerHTML = '<i class="fas fa-credit-card" style="margin-right: 8px;"></i>Pay with Card';
            }

            // Show modal
            modal.style.display = 'flex';

            // Clear previous values
            document.getElementById('jazzcashCNIC').value = '';
            document.getElementById('jazzcashMobile').value = '';
            document.getElementById('jazzcashMessage').style.display = 'none';
        }

        function closeJazzCashModal() {
            const modal = document.getElementById('jazzcashModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        async function processJazzCashPayment() {
            const package = @json($package ?? null);
            const userId = {{ $user ? $user->id : 'null' }};
            const applicationId = document.getElementById('applicationId').value;
            const paymentType = document.getElementById('jazzcashModal').getAttribute('data-payment-type');

            const cnic = document.getElementById('jazzcashCNIC').value.trim();
            const mobile = document.getElementById('jazzcashMobile').value.trim();
            const submitBtn = document.getElementById('jazzcashSubmitBtn');
            const messageEl = document.getElementById('jazzcashMessage');

            // Validation
            if (!cnic || cnic.length < 13) {
                messageEl.textContent = 'Please enter a valid CNIC (13 digits without dashes)';
                messageEl.style.color = '#dc2626';
                messageEl.style.display = 'block';
                return;
            }

            if (!mobile || mobile.length < 11) {
                messageEl.textContent = 'Please enter a valid mobile number (11 digits)';
                messageEl.style.color = '#dc2626';
                messageEl.style.display = 'block';
                return;
            }

            // Disable button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';
            messageEl.style.display = 'none';

            try {
                const endpoint = paymentType === 'wallet'
                    ? `${apiBaseUrl}/jazzcash/pay-with-wallet`
                    : `${apiBaseUrl}/jazzcash/card-payment`;

                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        application_package_id: package.id,
                        user_id: userId,
                        application_id: applicationId,
                        CNIC: cnic,
                        mobile: mobile,
                        amount: package.price
                    })
                });

                const data = await response.json();

                if (data.success && data.status === 'success') {
                    messageEl.textContent = 'Payment successful! Refreshing...';
                    messageEl.style.color = '#10b981';
                    messageEl.style.display = 'block';

                    // Close modal after 2 seconds
                    setTimeout(() => {
                        closeJazzCashModal();
                        // Reload page to update package status
                        window.location.reload();
                    }, 2000);
                } else {
                    messageEl.textContent = data.message || 'Payment failed. Please try again.';
                    messageEl.style.color = '#dc2626';
                    messageEl.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = paymentType === 'wallet'
                        ? '<i class="fas fa-wallet" style="margin-right: 8px;"></i>Pay with JazzCash Wallet'
                        : '<i class="fas fa-credit-card" style="margin-right: 8px;"></i>Pay with Card';
                }
            } catch (error) {
                console.error('JazzCash payment error:', error);
                messageEl.textContent = 'Network error. Please try again.';
                messageEl.style.color = '#dc2626';
                messageEl.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerHTML = paymentType === 'wallet'
                    ? '<i class="fas fa-wallet" style="margin-right: 8px;"></i>Pay with JazzCash Wallet'
                    : '<i class="fas fa-credit-card" style="margin-right: 8px;"></i>Pay with Card';
            }
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('jazzcashModal');
            if (modal && event.target === modal) {
                closeJazzCashModal();
            }
        });
    </script>
</body>

</html>
