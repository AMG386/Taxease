<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login • TaxEase</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="TaxEase - Professional Tax Management System">
  
  {{-- Vite Assets --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  
  <style>
    :root {
      --kt-primary: #009ef7;
      --kt-primary-light: #f1faff;
      --kt-primary-dark: #0084d1;
      --kt-success: #50cd89;
      --kt-danger: #f1416c;
      --kt-warning: #ffc700;
      --kt-info: #7239ea;
      --kt-gray-100: #f5f8fa;
      --kt-gray-200: #eff2f5;
      --kt-gray-300: #e4e6ea;
      --kt-gray-600: #7e8299;
      --kt-gray-700: #5e6278;
      --kt-gray-800: #1e2129;
      --kt-gray-900: #181c32;
    }

    * {
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
      min-height: 100vh;
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      position: relative;
    }

    /* Animated Background Particles */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: 
        radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(255,255,255,0.08) 0%, transparent 50%),
        radial-gradient(circle at 90% 10%, rgba(255,255,255,0.05) 0%, transparent 50%),
        radial-gradient(circle at 10% 90%, rgba(255,255,255,0.07) 0%, transparent 50%);
      animation: floatingParticles 20s ease-in-out infinite;
      pointer-events: none;
      z-index: 0;
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    @keyframes floatingParticles {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      33% { transform: translateY(-20px) rotate(120deg); }
      66% { transform: translateY(20px) rotate(240deg); }
    }

    /* Floating Geometric Shapes */
    .floating-shapes {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 1;
    }

    .shape {
      position: absolute;
      opacity: 0.1;
      animation: float 6s ease-in-out infinite;
    }

    .shape:nth-child(1) {
      top: 10%;
      left: 10%;
      width: 80px;
      height: 80px;
      background: linear-gradient(45deg, #ff6b6b, #feca57);
      border-radius: 50%;
      animation-delay: 0s;
    }

    .shape:nth-child(2) {
      top: 20%;
      right: 10%;
      width: 60px;
      height: 60px;
      background: linear-gradient(45deg, #48dbfb, #0abde3);
      transform: rotate(45deg);
      animation-delay: 2s;
    }

    .shape:nth-child(3) {
      bottom: 20%;
      left: 20%;
      width: 100px;
      height: 100px;
      background: linear-gradient(45deg, #ff9ff3, #f368e0);
      border-radius: 30%;
      animation-delay: 4s;
    }

    .shape:nth-child(4) {
      bottom: 30%;
      right: 20%;
      width: 70px;
      height: 70px;
      background: linear-gradient(45deg, #54a0ff, #2e86de);
      clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
      animation-delay: 1s;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-30px) rotate(180deg); }
    }

    .auth-wrapper {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
      position: relative;
      z-index: 2;
    }

    .auth-card {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(30px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 24px;
      box-shadow: 
        0 25px 80px rgba(0, 0, 0, 0.15),
        0 0 0 1px rgba(255, 255, 255, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.3);
      overflow: hidden;
      width: 100%;
      max-width: 420px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      animation: slideInUp 0.8s ease-out;
    }

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(60px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .auth-card:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: 
        0 35px 100px rgba(0, 0, 0, 0.2),
        0 0 0 1px rgba(255, 255, 255, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.4);
    }

    /* Glowing border effect */
    .auth-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      border-radius: 32px;
      padding: 2px;
      background: linear-gradient(45deg, 
        rgba(0, 158, 247, 0.3),
        rgba(118, 75, 162, 0.3),
        rgba(240, 147, 251, 0.3),
        rgba(0, 158, 247, 0.3)
      );
      background-size: 400% 400%;
      animation: gradientRotate 8s ease infinite;
      mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      mask-composite: xor;
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .auth-card:hover::before {
      opacity: 1;
    }

    @keyframes gradientRotate {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .auth-header {
      background: linear-gradient(135deg, 
        var(--kt-primary) 0%, 
        #3699ff 30%, 
        #7c3aed 70%, 
        #a855f7 100%
      );
      background-size: 400% 400%;
      animation: headerGradient 12s ease infinite;
      padding: 2.5rem 2rem 1.5rem;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    @keyframes headerGradient {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Animated mesh background */
    .auth-header::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 60%),
                  radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
                  radial-gradient(circle at 20% 80%, rgba(255,255,255,0.12) 0%, transparent 50%);
      animation: meshRotate 15s linear infinite;
    }

    @keyframes meshRotate {
      0% { transform: rotate(0deg) scale(1); }
      50% { transform: rotate(180deg) scale(1.1); }
      100% { transform: rotate(360deg) scale(1); }
    }

    .auth-logo {
      width: 70px;
      height: 70px;
      background: rgba(255, 255, 255, 0.25);
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 18px;
      margin: 0 auto 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      z-index: 1;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      animation: logoFloat 4s ease-in-out infinite;
    }

    @keyframes logoFloat {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-10px) rotate(5deg); }
    }

    .auth-logo::before {
      content: '';
      position: absolute;
      top: -2px;
      left: -2px;
      right: -2px;
      bottom: -2px;
      background: linear-gradient(45deg, 
        rgba(255,255,255,0.4),
        rgba(255,255,255,0.1),
        rgba(255,255,255,0.4)
      );
      border-radius: 24px;
      opacity: 0;
      transition: opacity 0.3s ease;
      animation: shimmerBorder 3s ease-in-out infinite;
    }

    .auth-logo:hover::before {
      opacity: 1;
    }

    @keyframes shimmerBorder {
      0% { background-position: -200% 0; }
      100% { background-position: 200% 0; }
    }

    .auth-logo i {
      font-size: 2.2rem;
      color: white;
      filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
      animation: iconPulse 3s ease-in-out infinite;
    }

    @keyframes iconPulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    .auth-title {
      font-size: 1.8rem;
      font-weight: 800;
      color: white;
      margin-bottom: 0.25rem;
      position: relative;
      z-index: 1;
      text-shadow: 0 4px 12px rgba(0,0,0,0.2);
      animation: titleGlow 2s ease-in-out infinite alternate;
      letter-spacing: 0.5px;
    }

    @keyframes titleGlow {
      from { text-shadow: 0 4px 12px rgba(0,0,0,0.2), 0 0 20px rgba(255,255,255,0.1); }
      to { text-shadow: 0 4px 12px rgba(0,0,0,0.2), 0 0 30px rgba(255,255,255,0.2); }
    }

    .auth-subtitle {
      color: rgba(255, 255, 255, 0.9);
      font-size: 0.95rem;
      font-weight: 500;
      position: relative;
      z-index: 1;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
      animation: subtitleFade 3s ease-in-out infinite;
    }

    @keyframes subtitleFade {
      0%, 100% { opacity: 0.9; }
      50% { opacity: 1; }
    }

    .auth-body {
      padding: 2rem;
      position: relative;
    }

    /* Enhanced Form Styling */
    .form-floating {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .form-floating .form-control {
      height: 55px;
      padding: 1.25rem 3.5rem 0.75rem 1.25rem;
      border: 2px solid var(--kt-gray-300);
      border-radius: 14px;
      font-size: 0.95rem;
      font-weight: 500;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .form-floating .form-control:focus {
      border-color: var(--kt-primary);
      box-shadow: 
        0 0 0 4px rgba(0, 158, 247, 0.15),
        0 8px 25px rgba(0, 158, 247, 0.1);
      transform: translateY(-2px) scale(1.01);
      background: white;
    }

    .form-floating .form-control:hover:not(:focus) {
      border-color: var(--kt-primary);
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .form-floating label {
      padding: 1.5rem 1.5rem 0.75rem;
      color: var(--kt-gray-600);
      font-weight: 600;
      font-size: 0.9rem;
      transition: all 0.3s ease;
      pointer-events: none;
    }

    .form-floating .form-control:focus ~ label,
    .form-floating .form-control:not(:placeholder-shown) ~ label {
      transform: scale(0.85) translateY(-0.5rem);
      color: var(--kt-primary);
    }

    .input-group-icon {
      position: absolute;
      right: 1.25rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--kt-gray-500);
      font-size: 1.3rem;
      z-index: 5;
      transition: all 0.3s ease;
    }

    .form-floating:focus-within .input-group-icon {
      color: var(--kt-primary);
      transform: translateY(-50%) scale(1.1);
    }

    .password-toggle {
      cursor: pointer;
      transition: all 0.3s ease;
      padding: 0.5rem;
      border-radius: 8px;
    }

    .password-toggle:hover {
      color: var(--kt-primary);
      background: rgba(0, 158, 247, 0.1);
      transform: translateY(-50%) scale(1.15);
    }

    /* Enhanced Input Validation */
    .form-control.is-invalid {
      border-color: var(--kt-danger);
      box-shadow: 0 0 0 3px rgba(241, 65, 108, 0.15);
      animation: shakeError 0.5s ease-in-out;
    }

    @keyframes shakeError {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }

    .form-control.is-valid {
      border-color: var(--kt-success);
      box-shadow: 0 0 0 3px rgba(80, 205, 137, 0.15);
    }

    .invalid-feedback {
      display: block;
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--kt-danger);
      margin-top: 0.5rem;
      animation: slideInDown 0.3s ease;
    }

    @keyframes slideInDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Enhanced Auth Options */
    .auth-options {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 2rem;
      padding: 0.75rem 0;
    }

    .custom-checkbox {
      display: flex;
      align-items: center;
      cursor: pointer;
      font-weight: 600;
      color: var(--kt-gray-700);
      padding: 0.5rem;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .custom-checkbox:hover {
      background: rgba(0, 158, 247, 0.05);
      color: var(--kt-primary);
    }

    .custom-checkbox input {
      width: 22px;
      height: 22px;
      margin-right: 0.75rem;
      accent-color: var(--kt-primary);
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .custom-checkbox input:checked {
      transform: scale(1.1);
    }

    .forgot-link {
      color: var(--kt-primary);
      text-decoration: none;
      font-weight: 600;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      transition: all 0.3s ease;
      position: relative;
    }

    .forgot-link::before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0;
      height: 2px;
      background: var(--kt-primary);
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    .forgot-link:hover {
      color: var(--kt-primary-dark);
      background: rgba(0, 158, 247, 0.05);
      transform: translateY(-2px);
    }

    .forgot-link:hover::before {
      width: 80%;
    }

    /* Ultra-Enhanced Submit Button */
    .btn-auth {
      width: 100%;
      height: 55px;
      background: linear-gradient(135deg, 
        var(--kt-primary) 0%, 
        #3699ff 25%, 
        #7c3aed 75%, 
        #a855f7 100%
      );
      background-size: 400% 400%;
      border: none;
      border-radius: 14px;
      color: white;
      font-weight: 700;
      font-size: 1rem;
      letter-spacing: 0.5px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      box-shadow: 
        0 8px 32px rgba(0, 158, 247, 0.3),
        0 0 0 1px rgba(255, 255, 255, 0.1);
      animation: buttonGradient 8s ease infinite;
    }

    @keyframes buttonGradient {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .btn-auth::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, 
        transparent, 
        rgba(255,255,255,0.4), 
        transparent
      );
      transition: left 0.6s ease;
    }

    .btn-auth:hover::before {
      left: 100%;
    }

    .btn-auth:hover {
      transform: translateY(-3px) scale(1.02);
      box-shadow: 
        0 15px 45px rgba(0, 158, 247, 0.4),
        0 0 0 1px rgba(255, 255, 255, 0.2),
        0 0 50px rgba(124, 58, 237, 0.3);
      animation-duration: 3s;
    }

    .btn-auth:active {
      transform: translateY(-1px) scale(1.01);
      transition: all 0.1s ease;
    }

    .btn-auth.loading {
      opacity: 0.8;
      pointer-events: none;
      animation: pulse 1.5s ease-in-out infinite;
    }

    @keyframes pulse {
      0% { box-shadow: 0 8px 32px rgba(0, 158, 247, 0.3); }
      50% { box-shadow: 0 8px 32px rgba(0, 158, 247, 0.6); }
      100% { box-shadow: 0 8px 32px rgba(0, 158, 247, 0.3); }
    }

    .btn-loading {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .btn-loading i {
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    /* Ultra-Enhanced Alert Styling */
    .alert {
      border: none;
      border-radius: 16px;
      padding: 1.25rem 1.5rem;
      margin-bottom: 2rem;
      font-weight: 600;
      position: relative;
      overflow: hidden;
      animation: slideInFromTop 0.5s ease-out;
    }

    @keyframes slideInFromTop {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      width: 4px;
      height: 100%;
      background: currentColor;
      opacity: 0.7;
    }

    .alert-success {
      background: linear-gradient(135deg, 
        rgba(80, 205, 137, 0.1) 0%, 
        rgba(80, 205, 137, 0.05) 100%
      );
      color: var(--kt-success);
      border: 1px solid rgba(80, 205, 137, 0.2);
      box-shadow: 0 4px 12px rgba(80, 205, 137, 0.1);
    }

    .alert-danger {
      background: linear-gradient(135deg, 
        rgba(241, 65, 108, 0.1) 0%, 
        rgba(241, 65, 108, 0.05) 100%
      );
      color: var(--kt-danger);
      border: 1px solid rgba(241, 65, 108, 0.2);
      box-shadow: 0 4px 12px rgba(241, 65, 108, 0.1);
    }

    .alert i {
      font-size: 1.2rem;
      margin-right: 0.5rem;
      animation: iconBounce 0.6s ease-out;
    }

    @keyframes iconBounce {
      0% { transform: scale(0.3); opacity: 0; }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); opacity: 1; }
    }

    /* Enhanced Features Grid */
    .features-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
      margin-top: 2rem;
      padding-top: 2rem;
      border-top: 2px solid transparent;
      background: linear-gradient(white, white) padding-box,
                  linear-gradient(90deg, 
                    rgba(0, 158, 247, 0.2),
                    rgba(124, 58, 237, 0.2),
                    rgba(0, 158, 247, 0.2)
                  ) border-box;
    }

    .feature-item {
      text-align: center;
      padding: 1rem 0.75rem;
      border-radius: 12px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      background: rgba(255, 255, 255, 0.5);
      backdrop-filter: blur(10px);
    }

    .feature-item::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, 
        rgba(0, 158, 247, 0.05) 0%, 
        rgba(124, 58, 237, 0.05) 100%
      );
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .feature-item:hover::before {
      opacity: 1;
    }

    .feature-item:hover {
      transform: translateY(-8px) scale(1.05);
      box-shadow: 0 12px 40px rgba(0, 158, 247, 0.15);
    }

    .feature-icon {
      width: 48px;
      height: 48px;
      background: linear-gradient(135deg, 
        var(--kt-primary-light) 0%, 
        rgba(0, 158, 247, 0.1) 100%
      );
      color: var(--kt-primary);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 0.75rem;
      font-size: 1.2rem;
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
    }

    .feature-icon::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, 
        transparent, 
        rgba(255, 255, 255, 0.4), 
        transparent
      );
      transition: left 0.6s ease;
    }

    .feature-item:hover .feature-icon::before {
      left: 100%;
    }

    .feature-item:hover .feature-icon {
      transform: scale(1.1) rotate(5deg);
      box-shadow: 0 8px 25px rgba(0, 158, 247, 0.2);
    }

    .feature-title {
      font-size: 0.875rem;
      font-weight: 700;
      color: var(--kt-gray-800);
      margin-bottom: 0.25rem;
      transition: all 0.3s ease;
    }

    .feature-item:hover .feature-title {
      color: var(--kt-primary);
    }

    .feature-desc {
      font-size: 0.75rem;
      color: var(--kt-gray-600);
      line-height: 1.4;
      transition: all 0.3s ease;
    }

    .feature-item:hover .feature-desc {
      color: var(--kt-gray-700);
    }

    /* Enhanced Footer */
    .auth-footer {
      text-align: center;
      margin-top: 2rem;
      color: rgba(255, 255, 255, 0.9);
      font-weight: 600;
      font-size: 0.875rem;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
      animation: footerGlow 3s ease-in-out infinite alternate;
    }

    @keyframes footerGlow {
      from { text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
      to { text-shadow: 0 2px 4px rgba(0,0,0,0.1), 0 0 20px rgba(255,255,255,0.1); }
    }

    /* Advanced Responsive Design */
    @media (max-width: 768px) {
      .auth-card {
        margin: 1rem;
        border-radius: 20px;
        max-width: 95%;
      }
      
      .auth-header {
        padding: 2rem 1.5rem 1.25rem;
      }
      
      .auth-body {
        padding: 1.5rem;
      }
      
      .auth-title {
        font-size: 1.6rem;
      }
      
      .auth-subtitle {
        font-size: 0.9rem;
      }
      
      .features-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
      }
      
      .form-floating .form-control {
        height: 52px;
        padding: 1.125rem 3rem 0.75rem 1.125rem;
      }
      
      .btn-auth {
        height: 52px;
        font-size: 0.95rem;
      }

      .auth-options {
        flex-direction: column;
        gap: 0.75rem;
        align-items: stretch;
      }

      .forgot-link {
        text-align: center;
      }
    }

    @media (max-width: 480px) {
      .auth-wrapper {
        padding: 1rem 0.5rem;
      }
      
      .auth-card {
        border-radius: 18px;
      }
      
      .auth-header {
        padding: 1.75rem 1.25rem 1rem;
      }
      
      .auth-body {
        padding: 1.25rem;
      }
      
      .auth-title {
        font-size: 1.5rem;
      }
      
      .features-grid {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
      }
    }

    /* Advanced Loading and Interaction States */
    .form-group-loading {
      position: relative;
    }

    .form-group-loading::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.8);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease;
    }

    .form-group-loading.loading::after {
      opacity: 1;
      pointer-events: all;
    }

    /* Accessibility Enhancements */
    .form-control:focus {
      outline: none;
    }

    .btn-auth:focus {
      outline: 2px solid var(--kt-primary);
      outline-offset: 2px;
    }

    @media (prefers-reduced-motion: reduce) {
      *,
      *::before,
      *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
      }
    }

    /* High contrast mode support */
    @media (prefers-contrast: high) {
      .auth-card {
        border: 2px solid var(--kt-gray-800);
      }
      
      .form-floating .form-control {
        border-width: 3px;
      }
      
      .btn-auth {
        border: 2px solid var(--kt-gray-800);
      }
    }
  </style>
</head>
<body>
  <!-- Floating Background Shapes -->
  <div class="floating-shapes">
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
  </div>
  <div class="auth-wrapper">
    <div class="auth-card">
      {{-- Auth Header --}}
      <div class="auth-header">
        <div class="auth-logo">
          <i class="ki-duotone ki-chart-pie-4">
            <span class="path1"></span>
            <span class="path2"></span>
            <span class="path3"></span>
          </i>
        </div>
        <h1 class="auth-title">TaxEase</h1>
        <p class="auth-subtitle">Professional Tax Management System</p>
      </div>

      {{-- Auth Body --}}
      <div class="auth-body">
        {{-- Status Messages --}}
        @if (session('status'))
          <div class="alert alert-success">
            <i class="ki-duotone ki-check-circle me-2">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
            {{ session('status') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="alert alert-danger">
            <i class="ki-duotone ki-cross-circle me-2">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
            @foreach ($errors->all() as $e)
              <div>{{ $e }}</div>
            @endforeach
          </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login.post') }}" autocomplete="off" id="loginForm">
          @csrf

          {{-- Email Field --}}
          <div class="form-floating">
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                   required autofocus value="{{ old('email') }}" placeholder="you@example.com">
            <label for="email">Email Address</label>
            <div class="input-group-icon">
              <i class="ki-duotone ki-sms">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
            </div>
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Password Field --}}
          <div class="form-floating">
            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                   required placeholder="••••••••">
            <label for="password">Password</label>
            <div class="input-group-icon password-toggle" onclick="togglePassword()">
              <i class="ki-duotone ki-eye" id="passwordIcon">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
              </i>
            </div>
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Auth Options --}}
          <div class="auth-options">
            <label class="custom-checkbox">
              <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
              Remember me
            </label>
            <a href="#" class="forgot-link">Forgot Password?</a>
          </div>

          {{-- Submit Button --}}
          <button type="submit" class="btn-auth" id="submitBtn">
            <span class="btn-text">Sign In to TaxEase</span>
            <span class="btn-loading d-none">
              <i class="ki-duotone ki-loading"></i>
              Signing In...
            </span>
          </button>
          
          {{-- Backup submit for accessibility --}}
          <noscript>
            <button type="submit" class="btn btn-primary w-100 mt-2">
              Sign In (No JavaScript)
            </button>
          </noscript>
        </form>

        {{-- Features Grid --}}
        <div class="features-grid">
          <div class="feature-item">
            <div class="feature-icon">
              <i class="ki-duotone ki-shield-check">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
            </div>
            <div class="feature-title">Secure</div>
            <div class="feature-desc">Enterprise-grade security for your tax data</div>
          </div>
          <div class="feature-item">
            <div class="feature-icon">
              <i class="ki-duotone ki-rocket">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
            </div>
            <div class="feature-title">Fast</div>
            <div class="feature-desc">Quick GST filing and return processing</div>
          </div>
          <div class="feature-item">
            <div class="feature-icon">
              <i class="ki-duotone ki-chart-line-up">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
            </div>
            <div class="feature-title">Smart</div>
            <div class="feature-desc">AI-powered tax compliance insights</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Footer --}}
    <div class="auth-footer">
      <p>&copy; {{ date('Y') }} TaxEase. All rights reserved.</p>
    </div>
  </div>

  {{-- JavaScript --}}
  <script>
    // Simple password toggle - no interference with form
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const passwordIcon = document.getElementById('passwordIcon');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.className = 'ki-duotone ki-eye-slash';
        passwordIcon.innerHTML = '<span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>';
      } else {
        passwordInput.type = 'password';
        passwordIcon.className = 'ki-duotone ki-eye';
        passwordIcon.innerHTML = '<span class="path1"></span><span class="path2"></span><span class="path3"></span>';
      }
    }

    // Minimal form submission handling - just loading state
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('loginForm');
      const submitBtn = document.getElementById('submitBtn');
      const btnText = submitBtn.querySelector('.btn-text');
      const btnLoading = submitBtn.querySelector('.btn-loading');
      
      form.addEventListener('submit', function(e) {
        console.log('Form submitting...');
        
        // Show loading state
        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
        
        // Re-enable after 5 seconds as safety
        setTimeout(() => {
          submitBtn.disabled = false;
          btnText.classList.remove('d-none');
          btnLoading.classList.add('d-none');
        }, 5000);
      });
    });
  </script>
  </script>
</body>
</html>
