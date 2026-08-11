<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>New Riskwisdom Loans Enquiry</title>
    </head>
    <body style="margin:0; padding:24px; background:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#13263d;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #dde6ef;">
            <tr>
                <td style="padding:24px 28px; background:#132b55; color:#ffffff;">
                    <h1 style="margin:0; font-size:24px; line-height:1.2;">New website enquiry</h1>
                    <p style="margin:8px 0 0; color:#d6e5ff; font-size:14px; line-height:1.6;">
                        @if (($details['lead_type'] ?? 'contact') === 'borrowing_power')
                            A new borrowing power calculator lead was submitted on the Riskwisdom Loans website.
                        @elseif (($details['lead_type'] ?? 'contact') === 'rate_review')
                            A new rate review request was submitted — fast callback requested.
                        @elseif (($details['lead_type'] ?? 'contact') === 'calendly')
                            A new Calendly booking was made — call this contact at the booked time.
                        @else
                            A new contact form enquiry was submitted on the Riskwisdom Loans website.
                        @endif
                    </p>
                </td>
            </tr>
            <tr>
                <td style="padding:28px;">
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:0 0 18px; width:50%; vertical-align:top;">
                                <p style="margin:0 0 6px; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#5e6f81;">First name</p>
                                <p style="margin:0; font-size:16px; line-height:1.5;">{{ $details['first_name'] }}</p>
                            </td>
                            <td style="padding:0 0 18px; width:50%; vertical-align:top;">
                                <p style="margin:0 0 6px; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#5e6f81;">Last name</p>
                                <p style="margin:0; font-size:16px; line-height:1.5;">{{ $details['last_name'] }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0 0 18px; width:50%; vertical-align:top;">
                                <p style="margin:0 0 6px; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#5e6f81;">Phone</p>
                                <p style="margin:0; font-size:16px; line-height:1.5;">{{ $details['phone'] }}</p>
                            </td>
                            <td style="padding:0 0 18px; width:50%; vertical-align:top;">
                                <p style="margin:0 0 6px; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#5e6f81;">Email</p>
                                <p style="margin:0; font-size:16px; line-height:1.5;">
                                    <a href="mailto:{{ $details['email'] }}" style="color:#1b63c8; text-decoration:none;">{{ $details['email'] }}</a>
                                </p>
                            </td>
                        </tr>
                        @if (! empty($details['loan_type']))
                        <tr>
                            <td style="padding:0 0 18px; width:50%; vertical-align:top;">
                                <p style="margin:0 0 6px; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#5e6f81;">Loan type</p>
                                <p style="margin:0; font-size:16px; line-height:1.5;">{{ config('riskwisdom.loan_types')[$details['loan_type']] ?? $details['loan_type'] }}</p>
                            </td>
                            <td style="padding:0 0 18px; width:50%; vertical-align:top;">
                                <p style="margin:0 0 6px; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#5e6f81;">Timeline</p>
                                <p style="margin:0; font-size:16px; line-height:1.5;">{{ config('riskwisdom.timelines')[$details['timeline']] ?? $details['timeline'] }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0 0 18px; width:50%; vertical-align:top;">
                                <p style="margin:0 0 6px; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#5e6f81;">State</p>
                                <p style="margin:0; font-size:16px; line-height:1.5;">{{ $details['state'] ?? '—' }}</p>
                            </td>
                            <td style="padding:0 0 18px; width:50%; vertical-align:top;">
                                <p style="margin:0 0 6px; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#5e6f81;">Source / intent</p>
                                <p style="margin:0; font-size:16px; line-height:1.5;">{{ $details['source'] ?: '—' }}</p>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="2" style="padding-top:8px;">
                                <p style="margin:0 0 8px; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#5e6f81;">Enquiry</p>
                                <div style="padding:18px; border-radius:14px; background:#f4f7fb; border:1px solid #dde6ef; font-size:15px; line-height:1.7; white-space:pre-line;">{{ $details['enquiry'] }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
