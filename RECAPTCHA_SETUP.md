# reCAPTCHA v3 Setup Instructions

## 1. Get reCAPTCHA v3 Keys

1. Go to [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
2. Sign in with your Google account
3. Click "Create" to add a new site
4. Choose **reCAPTCHA v3** "I'm not a robot" (Invisible)
5. Add your domain(s) (e.g., `localhost`, `yourdomain.com`)
6. Accept terms and submit
7. Copy your **Site Key** and **Secret Key**

## 2. Configure Environment Variables

1. Copy `env.example` to `.env` (if not already done)
2. Add your reCAPTCHA v3 keys:

```env
# reCAPTCHA v3 Configuration
recaptcha.sitekey=your_actual_site_key_here
recaptcha.secretkey=your_actual_secret_key_here
```

## 3. Test the Login Form

1. Visit your login page
2. **reCAPTCHA v3 is invisible** - no checkbox to click
3. Fill in username and password
4. Click Login - reCAPTCHA runs automatically
5. Form submits with security score

## 4. Backend Validation

In your `Auth` controller, you'll need to validate the reCAPTCHA v3 response and score:

```php
public function cek_login_user()
{
    // Get reCAPTCHA v3 response token
    $recaptchaResponse = $this->request->getPost('g-recaptcha-response');
    
    if (empty($recaptchaResponse)) {
        return redirect()->back()->with('error', 'reCAPTCHA verification failed');
    }
    
    // Verify with Google
    $secretKey = env('recaptcha.secretkey');
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}");
    $captchaSuccess = json_decode($verify);
    
    if (!$captchaSuccess->success) {
        return redirect()->back()->with('error', 'reCAPTCHA verification failed');
    }
    
    // Check score (0.0 = bot, 1.0 = human)
    $score = $captchaSuccess->score;
    if ($score < 0.5) { // Adjust threshold as needed
        return redirect()->back()->with('error', 'Aktivitas mencurigakan terdeteksi. Silakan coba lagi.');
    }
    
    // Continue with login logic...
}
```

## 5. reCAPTCHA v3 Features

- **Invisible** - No user interaction required
- **Score-based** - Returns 0.0 (bot) to 1.0 (human)
- **Action-based** - Tracks specific actions (login, register, etc.)
- **User-friendly** - No interruptions to user experience
- **Advanced AI** - Better bot detection than v2

## Notes

- reCAPTCHA v3 is now integrated into the login form
- The widget is invisible and runs automatically
- Form validation includes reCAPTCHA v3 execution
- Backend receives a score to determine if user is human
- No user interaction required - completely seamless
- Recommended score threshold: 0.5 (adjust based on your needs)
