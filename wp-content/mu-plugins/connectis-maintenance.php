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
    background:
      radial-gradient(60% 50% at 50% 20%, var(--navy2) 0%, var(--navy) 60%),
      var(--navy);
    color:#fff;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px;
    overflow-x:hidden;
  }
  .wrap{max-width:640px;width:100%;text-align:center;}
  .logo{
    width:220px;height:220px;border-radius:50%;object-fit:cover;background:#01040d;
    box-shadow:0 0 0 3px rgba(51,208,232,0.35), 0 20px 60px rgba(27,99,230,0.35);
    margin-bottom:32px;
  }
  h1{font-size:clamp(1.6rem,4vw,2.4rem);font-weight:700;letter-spacing:0.5px;line-height:1.3;margin-bottom:12px;}
  .badge{
    display:inline-block;font-size:0.78rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;
    color:var(--cyan);border:1px solid rgba(51,208,232,0.4);background:rgba(51,208,232,0.08);
    padding:6px 16px;border-radius:999px;margin-bottom:22px;
  }
  p.tag{color:var(--grey);font-size:1rem;line-height:1.6;margin-bottom:36px;}
  .services{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-bottom:40px;}
  .services span{font-size:0.82rem;color:var(--grey);border:1px solid rgba(207,216,232,0.25);padding:6px 14px;border-radius:999px;}
  .progress{width:100%;max-width:360px;height:6px;background:rgba(255,255,255,0.08);border-radius:999px;margin:0 auto 40px;overflow:hidden;}
  .progress-bar{height:100%;width:65%;border-radius:999px;background:linear-gradient(90deg,var(--blue),var(--cyan));animation:pulse 2.4s ease-in-out infinite;}
  @keyframes pulse{0%,100%{opacity:0.85;}50%{opacity:1;}}
  .contact{display:flex;flex-wrap:wrap;gap:14px;justify-content:center;}
  .contact a{color:#fff;text-decoration:none;font-weight:600;font-size:0.92rem;padding:12px 22px;border-radius:8px;transition:transform .15s ease;}
  .contact a.primary{background:linear-gradient(90deg,var(--blue),var(--blue-dark));box-shadow:0 8px 24px rgba(27,99,230,0.35);}
  .contact a.secondary{border:1px solid rgba(255,255,255,0.25);}
  .contact a:hover{transform:translateY(-2px);}
  footer{margin-top:56px;color:rgba(207,216,232,0.5);font-size:0.78rem;}
</style>
</head>
<body>
  <div class="wrap">
    <img src="<?php echo esc_url($logo_url); ?>" alt="Connectis Solutions" class="logo">
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
    <footer>&copy; <?php echo esc_html(date('Y')); ?> Connectis Solutions — Tous droits réservés</footer>
  </div>
</body>
</html>
    <?php
    exit;
});
