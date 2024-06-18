<?php

return [

    'welcome' => [
        'title_1' => 'Welcome to the application portal',
        'title_2' => 'of the student council of the BTU Cottbus-Senftenberg!',
        'description' => 'Here you can submit applications to the StuRa of the BTU Cottbus-Senftenberg, such as exemption from the Germany semester ticket.',

        'login' => 'Continue with BTU account',

        'no_btu_account' => 'You no longer have access to your BTU account? Then please send your certificate of exmatriculation to office@stura-btu.de',
    ],

    'dashboard' => [
        'title' => 'Dashboard',
        'hello' => 'Hello',
        'description' => 'You can submit your StuRa applications here!',
        'dticket_request' => 'Apply for exemption from the semester ticket',
        'dticket_section_title' => 'Your submitted semester ticket exemptions',
    ],

    'dticket' => [

        'alerts' => [
            'one_time_token' => [
                'title' => 'One-time link for submission used!',
                'message' => 'This request was submitted with a one-time link and therefore requires a more thorough examination of the documents and the facts.',
            ],
            'has_login_link' => [
                'title' => 'Existing login link!',
                'message' => 'There is a login link for the student, which can be used to log in. It may be a person with no access to their BTU account. For external accounts, no authorization for the semester ticket can be checked.',
            ],
            'no_dticket_entitlement' => [
                'title' => 'No entitlement to the semester ticket!',
                'message' => 'No entitlement to the Germany semester ticket could be found. Please check if the student is entitled to an exemption.',
            ],
            'multiple_submissions' => [
                'title' => 'Multiple submissions for the same semester!',
                'message' => 'Several applications have already been submitted for the same semester. Please check if the new application is necessary.',
            ],
        ],

        'about' => [
            'title' => 'Information about you',
            'description' => 'The following information is automatically taken from your BTU account. If there are errors, please contact Student Services.',
        ],

        'missing_config' => [
            'title' => 'Semester not yet configured',
            'description' => 'No configuration has been set up for the selected semester.',
            'message' => 'The configuration for the selected semester has not yet been set up. Please try again later or contact semesterticket@stura-btu.de so that we can set up the configuration for the semester.',
        ],

        'not_eligable' => [
            'title' => 'Ineligible',
            'description' => 'You are not eligible to request an exemption.',
            'message' => 'We were unable to determine that you were eligible/required to receive the Germany Semester Ticket, so you cannot apply for an exemption. If you think this is an error, please contact semesterticket@stura-btu.de',
        ],

        'exemption' => [
            'title' => 'Exemption information',
            'description' => 'Failure to use the Germany Semester Ticket does not constitute a claim to reimbursement of transport fees.',

            'months_help_text' => 'Only full unused months can be refunded. (The exception is April and May in the summer semester of 2024 if the application is submitted by May 5th, 2024.)',

            'reason_help_text' => 'Applications for a) and b) must be submitted by May 5th, 2024, otherwise they will be rejected.',
        ],

        'banking' => [
            'title' => 'Reimbursement information',
        ],

        'consent' => [
            'title' => 'Consents',

            'privacy_title' => 'Storage and processing of data',
            'privacy' => 'I agree that my data may be stored and processed for the exemption and reimbursment process.',

            'remove_permission_title' => 'Loss of ticket permission',
            'remove_permission' => 'I agree that my ticket authorization for the selected timeframe will be cancelled immediately after submitting the form. If the application is rejected, the ticket authorization will be restored.',

            'truth_title' => 'Truth of the information',
            'truth' => 'I confirm that all information provided is true and that I will not submit two exemption applications for the same semester.',
        ],

        'status' => [
            'title' => 'Status',
            'description' => 'Track your exemption status here. You will receive an email as soon as the status changes.',

            'pending' => 'Pending',
            'approved' => 'Approved',
            'paid' => 'Paid',
            'rejected' => 'Rejected',
        ],

    ],

    'fields' => [
        'name' => 'Name',
        'email' => 'E-Mail',
        'btu_id' => 'BTU-ID',
        'entitlements' => 'Entitlements',
        'is_admin' => 'Admin-Rights',
        'created_at' => 'Submitted',
        'status' => 'Status',
        'semester' => 'Semester',
        'months' => 'Period to be exempted',
        'reason' => 'Reason',
        'comment' => 'Comment',
        'attachments' => 'Attachments',
        'banking_name' => 'Account Holder',
        'banking_iban' => 'IBAN',
        'banking_bic' => 'BIC',
        'file' => 'File',
        'reason_for_rejection' => 'Reason for rejection',
        'exclude_starts_at' => 'Exemption starts at',
        'exclude_ends_at' => 'Exemption ends at',
        'banking_amount' => 'Reimbursement amount',
        'banking_reference' => 'Reference',
    ],

    'notifications' => [

        'submitted' => [
            'subject' => 'Receipt: Request for exemption from the semester ticket',
            'greeting' => 'Hello :name!',
            'line_1' => 'Your request for exemption from the semester ticket has been received.',
            'action' => 'View request',
            'line_2' => 'We will process your application as quickly as possible and inform you of the status.',
            'line_3' => 'Due to the high number of inquiries and requests relating to the semester ticket, processing may take some time. We ask for your understanding.',
            'salutation' => "Best regards  \nYour StuRa of the BTU Cottbus-Senftenberg",
        ],

        'status_changed' => [
            'subject' => 'Status change: Request for exemption from the semester ticket',
            'greeting' => 'Hello :name!',
            'line_1' => 'The status of your exemption request has changed to :status.',
            'action' => 'View request',
            'line_2' => 'You have one month to appeal this decision. If you have any questions, please contact us at semesterticket@stura-btu.de',
            'line_3' => 'We ask for your understanding that due to the high number of inquiries and applications relating to the semester ticket, we cannot respond to every inquiry immediately.',
            'salutation' => "Best regards  \nYour StuRa of the BTU Cottbus-Senftenberg",
        ],

        'one_time_link_created' => [
            'subject' => 'Login Link for StuRa BTU Application Portal',
            'greeting' => 'Hello :name!',
            'line_1' => 'You can now send in a request to the StuRa of the BTU Cottbus-Senftenberg using the provided login link.',
            'action' => 'Submit Request',
            'line_2' => 'Please note that this link is only valid until :expires_at. If you need a new link, please contact us at office@stura-btu.de',
            'salutation' => "Best regards  \nYour StuRa of the BTU Cottbus-Senftenberg",
        ],

    ],

];
