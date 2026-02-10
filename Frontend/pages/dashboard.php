<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SuperSaaS — Dashboard Premium</title>
  <style>
    :root {
      --bg-1: #09070f;
      --bg-2: #130d22;
      --bg-3: #1b1232;
      --surface: rgba(24, 19, 38, 0.72);
      --surface-strong: rgba(32, 23, 52, 0.88);
      --stroke: rgba(183, 163, 255, 0.16);
      --text-main: #f0ecff;
      --text-soft: #b8afdb;
      --violet: #8f67ff;
      --indigo: #6d82ff;
      --amber: #e6bc67;
      --mint: #72d8b1;
      --radius: 18px;
      --shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: "Inter", "Segoe UI", sans-serif;
      background:
        radial-gradient(circle at 12% 14%, rgba(124, 83, 255, 0.22), transparent 34%),
        radial-gradient(circle at 86% 8%, rgba(84, 101, 255, 0.18), transparent 30%),
        linear-gradient(140deg, var(--bg-1), var(--bg-2) 54%, #06060c);
      color: var(--text-main);
      min-height: 100vh;
      letter-spacing: 0.01em;
    }

    /* =========================
       SIDEBAR SUPER SAAS
       ========================= */
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      width: 74px;
      height: 100vh;
      background: linear-gradient(
        180deg,
        rgba(14, 11, 24, 0.92),
        rgba(9, 8, 15, 0.96)
      );
      border-right: 1px solid rgba(255,255,255,0.06);
      backdrop-filter: blur(10px);
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 18px 0;
      z-index: 100;
    }

    .side-top { margin-bottom: 26px; }

    .logo-dot {
      width: 14px;
      height: 14px;
      border-radius: 50%;
      background: radial-gradient(circle, var(--violet), var(--indigo));
      box-shadow: 0 0 18px rgba(143,103,255,0.55);
    }

    .side-nav {
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .side-link {
      width: 44px;
      height: 44px;
      border-radius: 14px;
      display: grid;
      place-items: center;
      text-decoration: none;
      font-size: 18px;
      color: #cfc6ff;
      background: transparent;
      border: 1px solid transparent;
      transition: all 280ms ease;
      position: relative;
    }

    .side-link:hover {
      background: rgba(143,103,255,0.14);
      border-color: rgba(143,103,255,0.38);
      box-shadow: 0 0 14px rgba(143,103,255,0.25);
      transform: translateY(-1px);
    }

    .side-link.active {
      background: linear-gradient(
        135deg,
        rgba(143,103,255,0.35),
        rgba(109,130,255,0.25)
      );
      border-color: rgba(143,103,255,0.55);
      box-shadow: 0 0 18px rgba(143,103,255,0.45);
    }

    /* Tooltip minimal (pro) */
    .side-link::after {
      content: attr(data-tip);
      position: absolute;
      left: 56px;
      top: 50%;
      transform: translateY(-50%);
      padding: 8px 10px;
      border-radius: 12px;
      background: rgba(10, 9, 17, 0.88);
      border: 1px solid rgba(255,255,255,0.08);
      color: #e7e1ff;
      font-size: 0.78rem;
      white-space: nowrap;
      opacity: 0;
      pointer-events: none;
      transition: opacity 220ms ease, transform 220ms ease;
      box-shadow: 0 14px 30px rgba(0,0,0,0.35);
    }

    .side-link:hover::after {
      opacity: 1;
      transform: translate(2px, -50%);
    }

    /* =========================
       DASHBOARD GRID
       ========================= */
    .dashboard {
      max-width: 1440px;
      margin: 0 auto;
      padding: 28px;
      margin-left: 74px; /* ✅ place for sidebar */
      display: grid;
      grid-template-columns: 2.3fr 0.95fr;
      grid-template-rows: auto auto auto;
      gap: 20px;
      grid-template-areas:
        "kpi side"
        "chart side"
        "bottom bottom";
    }

    .card {
      background: linear-gradient(145deg, var(--surface-strong), var(--surface));
      border: 1px solid var(--stroke);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      backdrop-filter: blur(8px);
      transition: transform 950ms ease, border-color 950ms ease, box-shadow 950ms ease, opacity 950ms ease;
      opacity: 0;
      transform: translateY(10px);
      animation: riseIn 980ms ease forwards;
    }

    .card:hover {
      transform: translateY(-2px);
      border-color: rgba(143, 103, 255, 0.46);
      box-shadow: 0 18px 35px rgba(72, 42, 145, 0.35);
    }

    @keyframes riseIn {
      to { opacity: 1; transform: translateY(0); }
    }

    .kpis {
      grid-area: kpi;
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 14px;
    }

    .kpi {
      padding: 18px;
      min-height: 142px;
      position: relative;
      overflow: hidden;
    }

    .kpi h3 {
      margin: 0;
      font-size: 0.9rem;
      color: var(--text-soft);
      font-weight: 500;
    }

    .kpi .value {
      margin: 10px 0 4px;
      font-size: 2rem;
      font-weight: 650;
      letter-spacing: -0.02em;
    }

    .kpi p {
      margin: 0;
      font-size: 0.82rem;
      color: var(--text-soft);
    }

    .ring {
      position: absolute;
      right: 14px;
      top: 14px;
      width: 58px;
      height: 58px;
      border-radius: 50%;
      background: conic-gradient(var(--accent) calc(var(--progress) * 1%), rgba(255, 255, 255, 0.08) 0);
      display: grid;
      place-items: center;
    }

    .ring::after {
      content: "";
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background: rgba(13, 10, 21, 0.94);
      border: 1px solid rgba(255,255,255,0.04);
    }

    .chart-wrap {
      grid-area: chart;
      padding: 20px 22px;
      min-height: 340px;
    }

    .section-title {
      margin: 0;
      font-size: 1rem;
      font-weight: 550;
    }

    .section-sub {
      margin: 6px 0 18px;
      color: var(--text-soft);
      font-size: 0.85rem;
    }

    .metrics-row {
      display: flex;
      gap: 12px;
      margin-top: 12px;
      flex-wrap: wrap;
    }

    .pill {
      border-radius: 999px;
      border: 1px solid rgba(255,255,255,0.09);
      background: rgba(11, 10, 18, 0.56);
      padding: 8px 12px;
      font-size: 0.8rem;
      color: var(--text-soft);
    }

    .pill strong {
      color: var(--text-main);
      font-weight: 600;
    }

    .side {
      grid-area: side;
      display: grid;
      gap: 14px;
      align-content: start;
    }

    .side-section {
      padding: 16px;
    }

    .profile {
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .avatar {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: linear-gradient(135deg, rgba(143,103,255,0.9), rgba(109,130,255,0.75));
      display: grid;
      place-items: center;
      font-weight: 700;
    }

    .small {
      margin: 3px 0 0;
      color: var(--text-soft);
      font-size: 0.8rem;
    }

    .activity,
    .suggestions {
      list-style: none;
      margin: 12px 0 0;
      padding: 0;
      display: grid;
      gap: 10px;
    }

    .activity li,
    .suggestions li {
      font-size: 0.85rem;
      line-height: 1.35;
      color: #d8d0fa;
      padding: 10px;
      border-radius: 12px;
      background: rgba(11, 10, 19, 0.5);
      border: 1px solid rgba(255,255,255,0.06);
    }

    .bottom {
      grid-area: bottom;
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 14px;
    }

    .panel {
      padding: 16px;
      min-height: 190px;
    }

    .panel ul {
      list-style: none;
      margin: 12px 0 0;
      padding: 0;
      display: grid;
      gap: 8px;
    }

    .panel li {
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 12px;
      background: rgba(10, 9, 17, 0.55);
      color: #ddd5fc;
      font-size: 0.83rem;
      padding: 10px;
      display: flex;
      justify-content: space-between;
      gap: 12px;
    }

    .btns {
      display: grid;
      gap: 10px;
      margin-top: 14px;
    }

    button {
      width: 100%;
      border: 1px solid rgba(143,103,255,0.44);
      background: rgba(30, 19, 52, 0.72);
      color: #f2eeff;
      border-radius: 12px;
      padding: 10px 12px;
      text-align: left;
      cursor: pointer;
      transition: box-shadow 900ms ease, border-color 900ms ease, transform 900ms ease;
    }

    button:hover {
      border-color: rgba(143,103,255,0.85);
      box-shadow: 0 0 18px rgba(143,103,255,0.35);
      transform: translateY(-1px);
    }

    .muted { color: var(--text-soft); }

    .accent-violet { --accent: var(--violet); }
    .accent-indigo { --accent: var(--indigo); }
    .accent-amber { --accent: var(--amber); }
    .accent-mint { --accent: var(--mint); }

    svg {
      width: 100%;
      height: 190px;
      overflow: visible;
      border-radius: 14px;
      background: linear-gradient(180deg, rgba(11, 10, 18, 0.65), rgba(9, 8, 15, 0.45));
      border: 1px solid rgba(255,255,255,0.06);
    }

    @media (max-width: 1150px) {
      .dashboard {
        grid-template-columns: 1fr;
        grid-template-areas:
          "kpi"
          "chart"
          "side"
          "bottom";
      }
      .kpis { grid-template-columns: repeat(2, minmax(0,1fr)); }
      .bottom { grid-template-columns: 1fr; }

      /* Sidebar remains fixed; we keep margin-left so content doesn't go behind */
      .side-link::after { display: none; }
    }

    @media (max-width: 560px) {
      .dashboard { padding: 18px; }
      .sidebar { width: 66px; }
      .dashboard { margin-left: 66px; }
    }
  </style>
</head>

<body>
  <!-- ✅ Sidebar (navigation SaaS) -->
  <aside class="sidebar" aria-label="Navigation SuperSaaS">
    <div class="side-top" aria-hidden="true">
      <div class="logo-dot"></div>
    </div>

    <nav class="side-nav">
      <a class="side-link" href="index.php" data-tip="Accueil">🏠</a>
      <a class="side-link active" href="dashboard.php" data-tip="Dashboard">📊</a>
      <a class="side-link" href="emails.php" data-tip="Emails">📧</a>
      <a class="side-link" href="documents.php" data-tip="Documents">📄</a>
      <a class="side-link" href="echeances.php" data-tip="Échéances">⏰</a>
      <a class="side-link" href="parametres.php" data-tip="Paramètres">⚙️</a>
    </nav>
  </aside>

  <!-- ✅ Dashboard -->
  <main class="dashboard">
    <section class="kpis">
      <article class="card kpi accent-violet" style="--progress:84; animation-delay: 80ms;">
        <div class="ring"></div>
        <h3>Documents organisés</h3>
        <div class="value">248</div>
        <p>+18 ce mois-ci</p>
      </article>

      <article class="card kpi accent-indigo" style="--progress:67; animation-delay: 160ms;">
        <div class="ring"></div>
        <h3>Abonnements actifs</h3>
        <div class="value">12</div>
        <p>3 optimisés automatiquement</p>
      </article>

      <article class="card kpi accent-mint" style="--progress:58; animation-delay: 240ms;">
        <div class="ring"></div>
        <h3>Échéances à venir</h3>
        <div class="value">7</div>
        <p>Sur les 30 prochains jours</p>
      </article>

      <article class="card kpi accent-amber" style="--progress:79; animation-delay: 320ms;">
        <div class="ring"></div>
        <h3>Score d'organisation</h3>
        <div class="value">79%</div>
        <p>Progression stable et sereine</p>
      </article>
    </section>

    <section class="card chart-wrap" style="animation-delay: 380ms;">
      <h2 class="section-title">Courbe d'optimisation SuperSaaS</h2>
      <p class="section-sub">Tendance de clarté administrative et réduction de charge mentale estimée.</p>

      <svg viewBox="0 0 800 260" role="img" aria-label="Courbe d'optimisation SuperSaaS">
        <defs>
          <linearGradient id="withGlow" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#7d63ff" />
            <stop offset="100%" stop-color="#72d8b1" />
          </linearGradient>
        </defs>

        <line x1="45" y1="215" x2="760" y2="215" stroke="rgba(255,255,255,0.18)" />
        <line x1="45" y1="30" x2="45" y2="215" stroke="rgba(255,255,255,0.18)" />

        <path d="M55 70 C130 110, 200 85, 280 130 S420 185, 520 175 S650 140, 740 200"
              fill="none" stroke="#6d82ff" stroke-opacity="0.8" stroke-width="4" stroke-linecap="round" />

        <path d="M55 195 C130 178, 210 165, 285 146 S420 102, 520 86 S655 58, 740 44"
              fill="none" stroke="url(#withGlow)" stroke-width="4.8" stroke-linecap="round" />

        <text x="62" y="54" fill="#9fb0ff" font-size="13">Avant SuperSaaS</text>
        <text x="572" y="66" fill="#c8bdff" font-size="13">Avec SuperSaaS</text>
        <text x="660" y="236" fill="rgba(255,255,255,0.58)" font-size="12">Temps</text>
      </svg>

      <div class="metrics-row">
        <span class="pill">Clarté améliorée de <strong>+37%</strong></span>
        <span class="pill">Potentiel d'optimisation restant : <strong>21%</strong></span>
      </div>
    </section>

    <aside class="side">
      <section class="card side-section" style="animation-delay: 440ms;">
        <div class="profile">
          <div class="avatar">AL</div>
          <div>
            <strong>Amélie Laurent</strong>
            <p class="small">Pilotage administratif continu</p>
          </div>
        </div>
      </section>

      <section class="card side-section" style="animation-delay: 500ms;">
        <h2 class="section-title">Activité récente</h2>
        <ul class="activity">
          <li>3 documents ajoutés et catégorisés automatiquement.</li>
          <li>2 échéances détectées et planifiées sans conflit.</li>
          <li>1 abonnement rapproché pour révision douce.</li>
        </ul>
      </section>

      <section class="card side-section" style="animation-delay: 560ms;">
        <h2 class="section-title">Suggestions SuperSaaS</h2>
        <ul class="suggestions">
          <li>SuperSaaS a identifié une opportunité d'optimisation sur votre contrat énergie.</li>
          <li>Une connexion email supplémentaire pourrait améliorer la détection de pièces jointes.</li>
        </ul>
      </section>
    </aside>

    <section class="bottom">
      <article class="card panel" style="animation-delay: 620ms;">
        <h2 class="section-title">Derniers documents</h2>
        <ul>
          <li><span>Facture Internet · Janvier</span><span class="muted">Aujourd'hui</span></li>
          <li><span>Avis d'imposition 2025</span><span class="muted">Hier</span></li>
          <li><span>Contrat habitation</span><span class="muted">2 jours</span></li>
        </ul>
      </article>

      <article class="card panel" style="animation-delay: 680ms;">
        <h2 class="section-title">Prochaines échéances</h2>
        <ul>
          <li><span>Assurance auto</span><span class="muted">12 fév.</span></li>
          <li><span>EDF mensualité</span><span class="muted">18 fév.</span></li>
          <li><span>Mutuelle santé</span><span class="muted">27 fév.</span></li>
        </ul>
      </article>

      <article class="card panel" style="animation-delay: 740ms;">
        <h2 class="section-title">Actions rapides</h2>
        <p class="section-sub">Gardez votre système fluide avec des actions immédiates.</p>
        <div class="btns">
          <button type="button">Importer un document</button>
          <button type="button">Connecter une boîte email</button>
          <button type="button">Ajouter une échéance</button>
        </div>
      </article>
    </section>
  </main>
</body>
</html>
