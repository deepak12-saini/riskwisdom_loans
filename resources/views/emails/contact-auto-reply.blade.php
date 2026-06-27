<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>We received your enquiry</title>
    </head>
    <body style="margin:0; padding:24px; background:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#13263d;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #dde6ef;">
            <tr>
                <td style="padding:28px;">
                    <h1 style="margin:0 0 12px; font-size:24px; color:#132b55;">Thank you, {{ $details['first_name'] }}</h1>
                    <p style="margin:0 0 18px; font-size:16px; line-height:1.7;">
                        @if (($details['lead_type'] ?? 'contact') === 'rate_review')
                            We have received your rate review request. {{ config('riskwisdom.rate_review.callback_promise') }}
                        @else
                            We have received your enquiry and a broker from Riskwisdom Loans will contact you within 24 hours.
                        @endif
                    </p>
                    <p style="margin:0 0 18px; font-size:16px; line-height:1.7;">
                        If your matter is urgent, call us on <a href="tel:{{ config('riskwisdom.phone_tel') }}" style="color:#1b63c8;">{{ config('riskwisdom.phone') }}</a>.
                    </p>
                    <p style="margin:0; font-size:14px; line-height:1.6; color:#5e6f81;">
                        {{ config('riskwisdom.legal_name') }} · {{ config('riskwisdom.email') }}
                    </p>
                </td>
            </tr>
        </table>
    </body>
</html>
