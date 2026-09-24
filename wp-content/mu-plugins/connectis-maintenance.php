<?php
/**
 * Plugin Name: Connectis — Page en construction
 * Description: Affiche une page d'attente aux visiteurs pendant la construction du site, sans bloquer l'accès à l'administration.
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('template_redirect', function () {
    if (is_user_logged_in() || is_admin() || (defined('REST_REQUEST') && REST_REQUEST) || (defined('DOING_CRON') && DOING_CRON) || (defined('WP_CLI') && WP_CLI)) {
        return;
    }

    $logo_url = content_url('mu-plugins/connectis-maintenance/logo-icon.jpeg');
    $contact_email = get_option('admin_email', 'contact@connectis-solutions.fr');

    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Retry-After: 3600');
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connectis Solutions — Site en construction</title>
<meta name="robots" content="noindex">
<link rel="icon" href="<?php echo esc_url($logo_url); ?>" type="image/jpeg">
<style>
  :root{
    --navy:#070d1f;
    --navy2:#0c1730;
    --blue:#1b63e6;
    --blue-dark:#123a8c;
    --cyan:#33d0e8;
    --grey:#cfd8e8;
  }
  *{box-sizing:border-box;margin:0;padding:0;}
  html,body{height:100%;}
  body{
    font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
    background:var(--navy);
    color:#fff;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px;
    overflow:hidden;
    position:relative;
  }

  /* Fond : halos animés + grille tech subtile */
  .bg{position:fixed;inset:0;z-index:0;overflow:hidden;}
  .bg::before{
    content:"";position:absolute;inset:0;
    background-image:
      linear-gradient(rgba(51,208,232,0.05) 1px, transparent 1px),
      linear-gradient(90deg, rgba(51,208,232,0.05) 1px, transparent 1px);
    background-size:42px 42px;
    mask-image:radial-gradient(70% 60% at 50% 40%, #000 30%, transparent 100%);
  }
  .blob{position:absolute;border-radius:50%;filter:blur(90px);opacity:0.55;}
  .blob-1{width:520px;height:520px;background:var(--blue);top:-160px;left:-120px;animation:drift1 22s ease-in-out infinite;}
  .blob-2{width:460px;height:460px;background:var(--cyan);bottom:-180px;right:-140px;opacity:0.35;animation:drift2 26s ease-in-out infinite;}
  .blob-3{width:360px;height:360px;background:var(--blue-dark);top:40%;left:60%;opacity:0.4;animation:drift3 30s ease-in-out infinite;}
  @keyframes drift1{0%,100%{transform:translate(0,0);}50%{transform:translate(60px,40px);}}
  @keyframes drift2{0%,100%{transform:translate(0,0);}50%{transform:translate(-50px,-30px);}}
  @keyframes drift3{0%,100%{transform:translate(0,0) scale(1);}50%{transform:translate(-40px,30px) scale(1.08);}}

  .card{
    position:relative;z-index:1;
    max-width:640px;width:100%;text-align:center;
    background:linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
    border:1px solid rgba(255,255,255,0.1);
    border-radius:28px;
    padding:56px 40px;
    backdrop-filter:blur(22px);
    -webkit-backdrop-filter:blur(22px);
    box-shadow:0 30px 80px rgba(0,0,0,0.45), inset 0 1px 0 rgba(255,255,255,0.06);
    animation:rise .7s cubic-bezier(.2,.8,.2,1) both;
  }
  @keyframes rise{from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);}}

  .logo-ring{
    position:relative;width:220px;height:220px;margin:0 auto 32px;
  }
  .logo-ring::before{
    content:"";position:absolute;inset:-10px;border-radius:50%;
    background:conic-gradient(from 0deg, var(--blue), var(--cyan), var(--blue));
    filter:blur(2px);opacity:0.55;
    animation:spin 8s linear infinite;
  }
  @keyframes spin{to{transform:rotate(360deg);}}
  .logo{
    position:relative;width:220px;height:220px;border-radius:50%;object-fit:contain;background:#01040d;padding:14px;
    box-shadow:0 0 0 4px var(--navy), 0 20px 60px rgba(27,99,230,0.4);
    display:block;
  }

  h1{font-size:clamp(1.6rem,4vw,2.4rem);font-weight:700;letter-spacing:0.5px;line-height:1.3;margin-bottom:12px;}
  .badge{
    display:inline-flex;align-items:center;gap:8px;font-size:0.78rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;
    color:var(--cyan);border:1px solid rgba(51,208,232,0.4);background:rgba(51,208,232,0.08);
    padding:7px 18px;border-radius:999px;margin-bottom:22px;
    box-shadow:0 0 0 0 rgba(51,208,232,0.5);
    animation:glow 2.6s ease-in-out infinite;
  }
  .badge::before{content:"";width:7px;height:7px;border-radius:50%;background:var(--cyan);box-shadow:0 0 8px var(--cyan);}
  @keyframes glow{0%,100%{box-shadow:0 0 0 0 rgba(51,208,232,0.35);}50%{box-shadow:0 0 0 8px rgba(51,208,232,0);}}

  p.tag{color:var(--grey);font-size:1rem;line-height:1.6;margin-bottom:36px;}
  .services{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-bottom:40px;}
  .services span{font-size:0.82rem;color:var(--grey);border:1px solid rgba(207,216,232,0.25);background:rgba(255,255,255,0.03);padding:6px 14px;border-radius:999px;transition:border-color .2s ease, color .2s ease;}
  .services span:hover{border-color:var(--cyan);color:#fff;}

  .progress{width:100%;max-width:360px;height:6px;background:rgba(255,255,255,0.08);border-radius:999px;margin:0 auto 40px;overflow:hidden;}
  .progress-bar{height:100%;width:65%;border-radius:999px;background:linear-gradient(90deg,var(--blue),var(--cyan));animation:pulse 2.4s ease-in-out infinite;}
  @keyframes pulse{0%,100%{opacity:0.85;}50%{opacity:1;}}

  .contact{display:flex;flex-wrap:wrap;gap:14px;justify-content:center;}
  .contact a{
    position:relative;overflow:hidden;
    color:#fff;text-decoration:none;font-weight:600;font-size:0.92rem;padding:14px 28px;border-radius:10px;
    transition:transform .2s ease, box-shadow .2s ease;
  }
  .contact a.primary{background:linear-gradient(90deg,var(--blue),var(--blue-dark));box-shadow:0 10px 30px rgba(27,99,230,0.4);}
  .contact a.primary::after{
    content:"";position:absolute;top:0;left:-75%;width:50%;height:100%;
    background:linear-gradient(120deg, transparent, rgba(255,255,255,0.35), transparent);
    transform:skewX(-20deg);
  }
  .contact a.primary:hover{transform:translateY(-3px);box-shadow:0 14px 36px rgba(27,99,230,0.55);}
  .contact a.primary:hover::after{animation:shine .9s ease;}
  @keyframes shine{to{left:125%;}}

  footer{margin-top:48px;color:rgba(207,216,232,0.5);font-size:0.78rem;position:relative;z-index:1;}

  @media (max-width:480px){
    .card{padding:40px 24px;border-radius:22px;}
    .logo-ring, .logo{width:150px;height:150px;}
  }
</style>
</head>
<body>
  <div class="bg">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
  </div>
  <div class="card">
    <div class="logo-ring">
      <img src="<?php echo esc_url($logo_url); ?>" alt="Connectis Solutions" class="logo">
    </div>
    <div class="badge">Site en construction</div>
    <h1>Notre nouveau site arrive bientôt</h1>
    <p class="tag">
      Connectis Solutions accompagne les professionnels dans la sécurisation, la connectivité<br>
      et l'équipement de leurs locaux. Proximité, réactivité, solutions sur mesure.
    </p>
    <div class="services">
      <span>Vidéosurveillance</span>
      <span>Téléphonie &amp; VoIP</span>
      <span>Internet &amp; Fibre optique</span>
      <span>Matériel informatique</span>
      <span>Abonnements</span>
      <span>Services &amp; maintenance</span>
    </div>
    <div class="progress"><div class="progress-bar"></div></div>
    <div class="contact">
      <a class="primary" href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a>
    </div>
  </div>
  <footer>&copy; <?php echo esc_html(date('Y')); ?> Connectis Solutions — Tous droits réservés</footer>
</body>
</html>
    <?php
    exit;
});
