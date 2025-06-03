# 📧 Mailtrap Setup Guide

## Step 1: Get Your Mailtrap Credentials

1. **Visit [Mailtrap.io](https://mailtrap.io)** and sign up for a free account
2. **Create a new inbox** (or use the default "My inbox")
3. **Go to your inbox** and click on "SMTP Settings"
4. **Copy the credentials** - you'll see something like:

```
Host: sandbox.smtp.mailtrap.io
Port: 2525
Username: 1a2b3c4d5e6f7g  (this will be your unique username)
Password: 9h8i7j6k5l4m3n  (this will be your unique password)
Auth: Plain
```

## Step 2: Update Your .env File

**I've already updated most settings, but you need to add your credentials:**

Open your `.env` file and replace the null values with your Mailtrap credentials:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username_here
MAIL_PASSWORD=your_mailtrap_password_here
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="Laravel"
```

## Step 3: Update Using PowerShell Command

Or run this PowerShell command (replace with your actual credentials):

```powershell
$content = Get-Content .env
$content = $content -replace 'MAIL_USERNAME=null', 'MAIL_USERNAME=your_username_here'
$content = $content -replace 'MAIL_PASSWORD=null', 'MAIL_PASSWORD=your_password_here'
$content | Set-Content .env
```

## Step 4: Clear Configuration Cache

After updating the credentials, run:

```bash
php artisan config:clear
```

## Step 5: Test the Setup

1. **Go to your application:** http://127.0.0.1:8000/forgot-password
2. **Enter an email** of an existing user
3. **Click "Send Password Reset Email"**
4. **Check your Mailtrap inbox** - you should see the email there!

## 🎯 Benefits of Using Mailtrap

✅ **See actual HTML emails** (not just plain text in logs)
✅ **Test email formatting** and styling
✅ **Check email headers** and metadata
✅ **Preview emails** in different email clients
✅ **Safe testing** - no risk of sending to real users
✅ **Free tier** includes 100 emails/month

## 🚨 Need Help?

If you need help getting your Mailtrap credentials, let me know and I can walk you through it step by step!

Once you have your credentials, just replace the `MAIL_USERNAME` and `MAIL_PASSWORD` values in your `.env` file. 