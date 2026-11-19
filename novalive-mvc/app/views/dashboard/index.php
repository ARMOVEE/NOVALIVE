<?php
/**
 * Halaman Ringkasan Dashboard — Gaya Quixotic
 * NOVA LIVE MVC — Langkah 11 Revisi
 */
$currentUser = $currentUser ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
$userInitials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $currentUser['full_name'] ?? 'AD'), 0, 2));
if (strlen($userInitials) < 2) $userInitials = 'AD';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="color-scheme" content="light" />
  <link rel="icon" href="data:," />
  <title><?= e($pageTitle ?? 'NOVA LIVE — Dashboard') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    button, [role="button"], select, summary, input:not([type="checkbox"], [type="radio"]) { min-height: 2rem; }
    :root{
      --navy:#0E3254;
      --navy-2:#15497A;
      --blue:#23649A;
      --sky:#509FC8;
      --bar:#B7D0E2;
      --bar-soft:#CFE0ED;
      --gold:#EAD4A2;
      --gold-deep:#D4B475;
      --gold-dark:#8C6D1F;
      --cream:#F5EFE0;
      --page:#E8EAEC;
      --panel:#F5F6F5;
      --card:#FFFFFF;
      --ink:#14232F;
      --muted:#7A8794;
      --line:#E7EBEE;
      --green:#2E9E5B;
      --green-bg:#E4F4EA;
      --red:#DC2626;
      --red-bg:#FDECEC;
      --radius:22px;
      --shadow:0 1px 2px rgba(14,50,84,.04),0 10px 30px rgba(14,50,84,.05);
      color-scheme:light;
    }
    *{box-sizing:border-box}
    html,body{margin:0;padding:0}
    body{
      font-family:'Inter',system-ui,sans-serif;
      background:var(--panel);
      color:var(--ink);
      -webkit-font-smoothing:antialiased;
      min-height:100vh;
      margin:0;
      padding:0;
    }
    h1,h2,h3,.pj{font-family:'Plus Jakarta Sans','Inter',sans-serif}
    button{font-family:inherit;cursor:pointer;border:none;background:none;color:inherit}
    a{text-decoration:none;color:inherit}
    .frame{
      width:100%;
      max-width:none;
      margin:0;
      background:var(--panel);
      border-radius:0;
      padding:16px 20px 24px;
      box-shadow:none;
      border:none;
    }

    /* ---------- Top bar ---------- */
    .topbar{
      display:flex;align-items:center;gap:18px;
      background:var(--card);
      border-radius:999px;
      padding:10px 12px 10px 20px;
      box-shadow:var(--shadow);
      position:relative;z-index:30;
    }
    .logo{display:flex;align-items:center;gap:9px;text-decoration:none;color:var(--navy);flex:none}
    .logo svg{display:block}
    .logo-ticket{height:40px;width:auto;overflow:visible;cursor:pointer;flex:none}
    .logo-word{font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:19px;letter-spacing:.2px;color:var(--navy);white-space:nowrap}
    .logo-word em{font-style:normal;color:var(--gold-deep)}
    .topnav{display:flex;gap:4px;margin:0 auto;background:#F1F3F4;border-radius:999px;padding:4px}
    .topnav a, .topnav button{
      padding:8px 18px;border-radius:999px;font-size:13.5px;font-weight:600;color:#5B6B78;min-height:36px;
      transition:background .15s,color .15s;text-decoration:none;display:inline-flex;align-items:center;
    }
    .topnav a:hover, .topnav button:hover{color:var(--navy)}
    .topnav a.active, .topnav button.active{background:var(--card);color:var(--navy);box-shadow:0 2px 8px rgba(14,50,84,.10)}
    .top-actions{display:flex;align-items:center;gap:6px;flex:none;margin-left:auto}
    .icon-btn{
      width:40px;height:40px;border-radius:50%;display:grid;place-items:center;color:var(--navy);
      position:relative;transition:background .15s;flex:none;
    }
    .icon-btn:hover{background:#EEF3F7}
    .icon-btn .dot{position:absolute;top:9px;right:10px;width:8px;height:8px;border-radius:50%;background:var(--gold-deep);border:2px solid #fff}
    .search-wrap{display:flex;align-items:center}
    .search-input{
      width:0;opacity:0;border:none;outline:none;background:#F1F3F4;border-radius:999px;
      font-size:13.5px;font-family:inherit;color:var(--ink);
      transition:width .25s ease,opacity .2s,padding .25s;
      padding:0;min-height:40px;
    }
    .search-wrap.open .search-input{width:170px;opacity:1;padding:0 14px;margin-right:2px}
    .avatar-btn{display:flex;align-items:center;gap:2px;border-radius:999px;padding:2px 4px 2px 2px}
    .avatar-btn:hover{background:#EEF3F7}
    .avatar{
      width:38px;height:38px;border-radius:50%;flex:none;
      background:linear-gradient(135deg,var(--navy-2),var(--navy));
      color:var(--gold);display:grid;place-items:center;
      font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:13px;
      border:2px solid var(--gold);
    }
    .pop{
      position:absolute;top:calc(100% + 10px);right:12px;width:300px;max-width:calc(100vw - 40px);
      background:var(--card);border-radius:18px;box-shadow:0 18px 44px rgba(14,50,84,.16);
      border:1px solid var(--line);padding:10px;display:none;z-index:50;
    }
    .pop.open{display:block}
    .pop h3{margin:6px 8px 8px;font-size:13px;color:var(--navy)}
    .notif{display:flex;gap:10px;padding:9px 8px;border-radius:12px}
    .notif:hover{background:#F4F7F9}
    .notif .n-ico{width:34px;height:34px;border-radius:10px;background:var(--cream);display:grid;place-items:center;flex:none;color:var(--gold-deep)}
    .notif p{margin:0;font-size:12.5px;line-height:1.45;color:var(--ink)}
    .notif span{display:block;color:var(--muted);font-size:11.5px;margin-top:1px}

    /* ---------- Greeting row ---------- */
    .greet{display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin:26px 4px 20px}
    .greet h1{font-size:clamp(24px,3.4vw,34px);font-weight:600;margin:0;color:#3A4750;letter-spacing:-.3px}
    .greet h1 strong{font-weight:800;color:var(--ink)}
    .greet-actions{margin-left:auto;display:flex;gap:10px;align-items:center;flex-wrap:wrap}
    .range-wrap{position:relative}
    .range-btn{
      display:flex;align-items:center;gap:9px;background:var(--card);border-radius:999px;
      padding:10px 16px;font-size:13px;font-weight:600;color:var(--ink);box-shadow:var(--shadow);min-height:42px;
    }
    .range-btn svg{color:var(--navy)}
    .range-menu{
      position:absolute;top:calc(100% + 8px);right:0;background:var(--card);border:1px solid var(--line);
      border-radius:16px;box-shadow:0 18px 44px rgba(14,50,84,.16);padding:6px;display:none;z-index:40;min-width:230px;
    }
    .range-menu.open{display:block}
    .range-menu button{display:flex;width:100%;text-align:left;padding:9px 12px;border-radius:10px;font-size:13px;font-weight:500;min-height:38px;align-items:center}
    .range-menu button:hover{background:#F4F7F9}
    .range-menu button.sel{background:var(--cream);color:var(--navy);font-weight:700}
    .btn-primary{
      display:flex;align-items:center;gap:8px;background:var(--navy);color:#fff;
      border-radius:999px;padding:10px 20px;font-size:13.5px;font-weight:700;min-height:42px;
      box-shadow:0 10px 22px rgba(14,50,84,.25);transition:background .15s,transform .1s;
      font-family:'Plus Jakarta Sans',sans-serif;text-decoration:none;
    }
    .btn-primary:hover{background:var(--navy-2);color:#fff}
    .btn-primary:active{transform:scale(.98)}

    /* ---------- Layout & rail ---------- */
    .layout{display:flex;gap:18px;align-items:flex-start}
    .rail{
      flex:none;width:62px;background:var(--card);border-radius:999px;box-shadow:var(--shadow);
      display:flex;flex-direction:column;align-items:center;padding:12px 0;gap:5px;
      position:sticky;top:16px;max-height:calc(100vh - 32px);overflow-y:auto;scrollbar-width:none;z-index:20;
    }
    .rail::-webkit-scrollbar{display:none}
    .rail a, .rail button{
      width:44px;height:44px;border-radius:50%;display:grid;place-items:center;color:#8A97A3;transition:all .15s;position:relative;flex:none;text-decoration:none;
    }
    .rail a:hover, .rail button:hover{color:var(--navy);background:#EEF3F7}
    .rail a.active, .rail button.active{background:var(--navy);color:var(--gold);box-shadow:0 8px 18px rgba(14,50,84,.28)}
    #railTip{
      position:fixed;z-index:200;background:var(--navy);color:#fff;font-size:12px;font-weight:600;
      padding:6px 11px;border-radius:8px;white-space:nowrap;pointer-events:none;
      opacity:0;transform:translateY(-50%) translateX(-4px);transition:opacity .15s,transform .15s;
      box-shadow:0 8px 20px rgba(14,50,84,.25);
    }
    #railTip.show{opacity:1;transform:translateY(-50%) translateX(0)}
    .rail-badge{
      position:absolute;top:1px;right:1px;min-width:17px;height:17px;border-radius:99px;
      background:var(--gold-deep);color:#fff;font-size:10px;font-weight:800;line-height:1;
      display:grid;place-items:center;padding:0 4px;border:2px solid #fff;font-family:'Plus Jakarta Sans',sans-serif;
    }
    .rail button.active .rail-badge, .rail a.active .rail-badge{border-color:var(--navy)}
    .rail-sep{width:26px;height:1px;background:var(--line);margin:5px 0;flex:none}
    .rail .spacer{flex:1;min-height:14px}
    .views{flex:1;min-width:0}
    .view{display:block;animation:viewIn .25s ease}
    @keyframes viewIn{from{opacity:0;transform:translateY(5px)}to{opacity:1;transform:none}}
    .grid{
      display:grid;gap:18px;
      grid-template-columns:296px minmax(0,1fr) 296px;
      grid-template-areas:"left chart right" "history history right";
    }
    .col-left{grid-area:left;display:flex;flex-direction:column;gap:18px;min-width:0}
    .col-right{grid-area:right;display:flex;flex-direction:column;gap:18px;min-width:0}
    .chart-card{grid-area:chart}
    .history-card{grid-area:history}
    .card{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px}
    .card-head{display:flex;align-items:flex-start;gap:10px;margin-bottom:14px}
    .card-head .h-ico{
      width:34px;height:34px;border-radius:10px;background:#F1F3F4;display:grid;place-items:center;color:var(--navy);flex:none;
    }
    .card-head h2{font-size:15px;font-weight:700;margin:0;color:var(--ink)}
    .card-head p{margin:2px 0 0;font-size:12px;color:var(--muted)}
    .expand-btn{margin-left:auto;width:32px;height:32px;border-radius:50%;display:grid;place-items:center;color:#8A97A3;flex:none}
    .expand-btn:hover{background:#EEF3F7;color:var(--navy)}

    /* ---------- Ticket card (ringkasan) ---------- */
    .ticket{
      border-radius:18px;color:#fff;position:relative;overflow:hidden;
      background:linear-gradient(120deg,var(--navy-2) 0%,var(--navy) 62%,#0A2745 100%);
      padding:18px 18px 16px;
    }
    .ticket::after{
      content:"";position:absolute;right:-46px;top:-46px;width:150px;height:150px;border-radius:50%;
      background:radial-gradient(circle,rgba(234,212,162,.30),rgba(234,212,162,0) 70%);
    }
    .ticket-top{display:flex;align-items:center;justify-content:space-between;position:relative;z-index:1}
    .ticket-brand{font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:16px;letter-spacing:.4px}
    .ticket-brand em{font-style:normal;color:var(--gold)}
    .ticket-sub{font-size:11px;color:#B9CFE2;margin-top:2px;letter-spacing:.3px}
    .ticket-amount{font-family:'Plus Jakarta Sans',sans-serif;font-size:27px;font-weight:800;margin:16px 0 14px;position:relative;z-index:1;letter-spacing:.2px}
    .ticket-amount small{display:block;font-family:'Inter',sans-serif;font-size:11px;font-weight:500;color:#B9CFE2;margin-bottom:5px;letter-spacing:.4px}
    .ticket-meta{display:flex;justify-content:space-between;align-items:flex-end;font-size:12px;color:#D7E4EF;position:relative;z-index:1}
    .ticket-meta .num{letter-spacing:2.5px;font-weight:600}
    .ticket-meta .exp{text-align:right}
    .ticket-meta .exp span{display:block;font-size:9.5px;color:#93AEC6;letter-spacing:1.2px}
    .perf{display:flex;gap:5px;margin-top:14px;position:relative;z-index:1}
    .perf i{width:5px;height:5px;border-radius:50%;background:rgba(255,255,255,.35)}
    .weekly{display:flex;align-items:center;gap:10px;margin-top:16px;padding-top:15px;border-top:1px solid var(--line)}
    .weekly p{margin:0;font-size:12.5px;color:var(--muted)}
    .weekly strong{display:block;font-family:'Plus Jakarta Sans',sans-serif;font-size:19px;color:var(--ink);margin-top:2px}
    .badge-pct{
      margin-left:auto;background:var(--green-bg);color:var(--green);font-size:12px;font-weight:700;
      padding:5px 11px;border-radius:999px;white-space:nowrap;
    }

    /* ---------- Bar chart (ringkasan) ---------- */
    .seg{display:flex;background:#F1F3F4;border-radius:999px;padding:3px;gap:2px}
    .seg button{padding:6px 14px;border-radius:999px;font-size:12px;font-weight:600;color:#5B6B78;min-height:32px}
    .seg button.active{background:var(--navy);color:#fff;box-shadow:0 4px 10px rgba(14,50,84,.25)}
    .chart-area{display:flex;gap:8px;margin-top:8px;padding-top:36px}
    .y-axis{display:flex;flex-direction:column;justify-content:space-between;height:218px;padding:2px 0 24px;font-size:11px;color:var(--muted);text-align:right;width:30px;flex:none}
    .bars{flex:1;display:flex;align-items:flex-end;gap:6px;height:218px;border-bottom:1px solid var(--line);position:relative}
    .bar-col{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;height:100%;position:relative}
    .bar{
      width:min(52px,72%);border-radius:999px 999px 10px 10px;background:var(--bar);
      transition:height .6s cubic-bezier(.22,.8,.3,1);position:relative;min-height:8px;
    }
    .bar-col:nth-child(odd) .bar{background:var(--bar-soft)}
    .bar-col.hot .bar{
      background:repeating-linear-gradient(45deg,var(--navy) 0 7px,#123f66 7px 14px);
    }
    .bar .tip{
      position:absolute;bottom:calc(100% + 13px);left:50%;transform:translateX(-50%);
      background:var(--navy);color:var(--gold);font-size:11px;font-weight:700;
      padding:4px 9px;border-radius:999px;white-space:nowrap;display:none;
    }
    .bar-col.hot .tip{display:block}
    .bar .tip::after{
      content:"";position:absolute;top:100%;left:50%;transform:translateX(-50%);
      width:9px;height:9px;border-radius:50%;background:var(--gold-deep);border:2px solid #fff;
      box-shadow:0 1px 4px rgba(14,50,84,.3);margin-top:3px;
    }
    .bar-labels{display:flex;gap:6px;margin-left:38px;margin-top:9px}
    .bar-labels span{flex:1;text-align:center;font-size:11px;font-weight:600;color:var(--muted);letter-spacing:.6px}
    .bar-labels span.hot{color:var(--navy)}
    .chart-foot{margin-top:14px;font-size:12.5px;color:var(--muted)}
    .chart-foot strong{color:var(--navy)}

    /* ---------- Saldo / area chart ---------- */
    .saldo-label{font-size:12.5px;color:var(--muted);text-align:center}
    .saldo-amount{font-family:'Plus Jakarta Sans',sans-serif;font-size:29px;font-weight:800;text-align:center;color:var(--ink);margin:3px 0 6px}
    .area-chart{width:100%;height:auto;display:block}
    .saldo-actions{display:flex;gap:10px;margin-top:12px}
    .btn-navy{
      flex:1;display:flex;align-items:center;justify-content:center;gap:7px;background:var(--navy);color:#fff;
      border-radius:999px;padding:10px;font-size:13px;font-weight:700;min-height:42px;
      font-family:'Plus Jakarta Sans',sans-serif;transition:background .15s;
    }
    .btn-navy:hover{background:var(--navy-2);color:#fff}
    .btn-ghost{
      flex:1;display:flex;align-items:center;justify-content:center;gap:7px;background:transparent;color:var(--navy);
      border-radius:999px;padding:10px;font-size:13px;font-weight:600;min-height:42px;transition:background .15s;
    }
    .btn-ghost:hover{background:#EEF3F7}
    .big-amount{font-family:'Plus Jakarta Sans',sans-serif;font-size:27px;font-weight:800;color:var(--ink)}
    .sub-card{border-top:1px solid var(--line);margin-top:16px;padding-top:15px}
    .sub-head{display:flex;align-items:flex-start;gap:8px}
    .sub-head h3{font-size:13.5px;margin:0}
    .sub-head p{margin:2px 0 0;font-size:11.5px;color:var(--muted)}
    .ava-stack{display:flex;align-items:center;margin-top:13px}
    .ava{
      width:40px;height:40px;border-radius:50%;border:2.5px solid #fff;display:grid;place-items:center;
      font-size:12px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;color:#fff;margin-left:-9px;
    }
    .ava:first-child{margin-left:0}
    .ava.more{background:var(--navy);color:var(--gold);font-size:12.5px}

    /* ---------- History table (ringkasan) ---------- */
    table{width:100%;border-collapse:collapse}
    thead th{
      text-align:left;font-size:11.5px;font-weight:600;color:var(--muted);letter-spacing:.4px;
      padding:4px 10px 10px;border-bottom:1px solid var(--line);white-space:nowrap;
    }
    thead th:last-child,tbody td:last-child{text-align:right}
    tbody td{padding:13px 10px;border-bottom:1px solid #F0F2F4;font-size:13.5px;vertical-align:middle;white-space:nowrap}
    tbody tr:last-child td{border-bottom:none}
    .ev-cell{display:flex;align-items:center;gap:11px;white-space:normal}
    .ev-ava{
      width:38px;height:38px;border-radius:50%;flex:none;display:grid;place-items:center;color:#fff;
      font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:13px;
    }
    .ev-name{font-weight:600;color:var(--ink);font-size:13.5px}
    .ev-sub{font-size:11.5px;color:var(--muted);font-weight:500;margin-top:1px}
    .status{display:inline-flex;align-items:center;gap:7px;font-weight:500}
    .status i{width:7px;height:7px;border-radius:50%;background:var(--green);flex:none}
    .amount{font-weight:700;color:var(--ink)}
    .empty-row td{text-align:center;color:var(--muted);padding:26px 10px;white-space:normal}

    /* ---------- Grid 2 & Best row ---------- */
    .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:18px}
    .best-row{display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid var(--line)}
    .best-row:last-child{border-bottom:0}
    .best-thumb{width:42px;height:42px;border-radius:12px;color:#fff;display:grid;place-items:center;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:13px;flex:none}
    .best-info{flex:1;min-width:0}
    .best-name{font-weight:600;font-size:13.5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .best-meta{font-size:12px;color:var(--muted);margin-top:1px}
    .best-pct{font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:16px;color:var(--navy);text-align:right}
    .best-pct small{display:block;font-size:10.5px;color:var(--muted);font-weight:500;font-family:'Inter',sans-serif}
    .progress{height:7px;border-radius:99px;background:#EDF1F3;margin-top:6px;overflow:hidden}
    .progress i{display:block;height:100%;border-radius:99px;background:var(--gold-deep)}
    .badge{display:inline-flex;align-items:center;gap:6px;font-size:11.5px;font-weight:700;border-radius:999px;padding:4px 11px;white-space:nowrap}
    .b-live{background:var(--green-bg);color:var(--green)}
    .b-wait{background:var(--cream);color:var(--gold-dark)}
    .b-refund{background:var(--red-bg);color:var(--red)}

    /* ---------- Toast ---------- */
    .toast{
      position:fixed;left:50%;bottom:26px;transform:translate(-50%,80px);z-index:120;
      background:var(--navy);color:#fff;font-size:13.5px;font-weight:600;
      padding:13px 22px;border-radius:999px;box-shadow:0 16px 36px rgba(14,50,84,.35);
      transition:transform .35s cubic-bezier(.22,.8,.3,1);max-width:calc(100vw - 32px);text-align:center;
    }
    .toast.show{transform:translate(-50%,0)}
    .toast em{font-style:normal;color:var(--gold)}

    /* ---------- Responsive ---------- */
    @media (max-width:1120px){
      .grid{grid-template-columns:270px minmax(0,1fr);grid-template-areas:"left chart" "right right" "history history"}
      .col-right{flex-direction:row}
      .col-right>.card{flex:1}
    }
    @media (max-width:900px){
      .grid-2{grid-template-columns:1fr}
    }
    @media (max-width:820px){
      .topnav{display:none}
      .grid{grid-template-columns:minmax(0,1fr);grid-template-areas:"left" "chart" "right" "history"}
      .col-right{flex-direction:column}
      .layout{flex-direction:column}
      .rail{
        flex-direction:row;flex-wrap:wrap;width:100%;border-radius:22px;padding:10px 12px;
        position:static;max-height:none;overflow:visible;justify-content:flex-start;gap:6px;
      }
      .rail .spacer{display:none}
      .rail-sep{display:none}
      .rail a, .rail button{width:42px;height:42px}
      #railTip{display:none}
    }
    @media (max-width:640px){
      body{padding:0}
      .frame{padding:10px 10px 16px;border-radius:0}
      .topbar{padding:8px 8px 8px 14px}
      .logo-ticket{height:34px}
      .logo-word{font-size:17px}
      .greet{margin:18px 2px 14px}
      .greet-actions{margin-left:0;width:100%}
      .range-wrap{flex:1}
      .range-btn{width:100%;justify-content:center}
      .btn-primary{flex:1;justify-content:center}
      .search-wrap.open .search-input{width:110px}
      thead{display:none}
      table,tbody,tr,td{display:block;width:100%}
      tbody tr{padding:12px 2px;border-bottom:1px solid var(--line)}
      tbody tr:last-child{border-bottom:none}
      tbody td{border:none;padding:3px 2px;white-space:normal}
      tbody td::before{
        content:attr(data-label);display:block;font-size:10.5px;font-weight:600;color:var(--muted);
        letter-spacing:.5px;text-transform:uppercase;margin-bottom:1px;
      }
      tbody td:last-child{text-align:left}
      .ev-cell{padding-bottom:4px}
      .ev-cell::before{display:none}
      .empty-row td::before{display:none}
    }
    @media (prefers-reduced-motion:reduce){
      .bar{transition:none}
      .toast{transition:none}
      .view{animation:none}
    }
  </style>
</head>
<body>
  <div class="frame">

    <?php require __DIR__ . '/sidebar.php'; ?>

    <!-- ========== VIEW: RINGKASAN ========== -->
    <section class="view active" id="view-ringkasan" aria-label="Ringkasan">
      <div class="grid">

        <!-- LEFT: ticket card + weekly -->
        <div class="col-left">
          <section class="card" aria-labelledby="targetTitle">
            <div class="card-head">
              <div>
                <h2 id="targetTitle">Target Penjualan</h2>
                <p>Total target penjualan tiket</p>
              </div>
              <button class="expand-btn js-soon" type="button" data-feature="Keuangan" aria-label="Lihat detail keuangan">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
              </button>
            </div>

            <div class="ticket">
              <div class="ticket-top">
                <div>
                  <div class="ticket-brand">NOVA <em>LIVE</em></div>
                  <div class="ticket-sub">KARTU TIKET EVENT</div>
                </div>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#EAD4A2" stroke-width="2" stroke-linecap="round"><path d="M6 9a9 9 0 0 1 0 6M9.5 6.5a13 13 0 0 1 0 11M13 4a17 17 0 0 1 0 16" transform="rotate(90 12 12)"/></svg>
              </div>
              <div class="ticket-amount"><small>TOTAL PENDAPATAN</small><?= rupiah($totalRevenue) ?></div>
              <div class="ticket-meta">
                <span class="num">•••• 9090</span>
                <span class="exp"><span>BERLAKU S.D.</span>09/26</span>
              </div>
              <div class="perf" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
            </div>

            <div class="weekly">
              <div>
                <p>Tiket Terjual</p>
                <strong><?= number_format($totalSold, 0, ',', '.') ?></strong>
              </div>
              <span class="badge-pct">+2,8%</span>
            </div>
          </section>
        </div>

        <!-- CENTER: bar chart -->
        <section class="card chart-card" aria-labelledby="chartTitle">
          <div class="card-head">
            <span class="h-ico">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="17" rx="3"/><path d="M3 9h18M8 2v4M16 2v4M8 15v3M12 13v5M16 15v3"/></svg>
            </span>
            <div>
              <h2 id="chartTitle">Penjualan Tiket</h2>
              <p>Tiket terjual per bulan (ribuan)</p>
            </div>
            <div style="margin-left:auto;display:flex;align-items:center;gap:6px">
              <div class="seg" role="group" aria-label="Periode grafik">
                <button type="button" id="segBulanan" class="active">Bulanan</button>
                <button type="button" id="segTahunan">Tahunan</button>
              </div>
              <button class="expand-btn js-soon" type="button" data-feature="Analitik" aria-label="Lihat laporan analitik" style="margin-left:0">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
              </button>
            </div>
          </div>

          <div class="chart-area">
            <div class="y-axis" aria-hidden="true"><span>5rb</span><span>4rb</span><span>3rb</span><span>2rb</span><span>1rb</span><span>0</span></div>
            <div class="bars" id="bars" role="img" aria-label="Grafik batang penjualan tiket"></div>
          </div>
          <div class="bar-labels" id="barLabels"></div>
          <p class="chart-foot" id="chartFoot"></p>
        </section>

        <!-- RIGHT: saldo + pendapatan -->
        <div class="col-right">
          <section class="card" aria-labelledby="saldoTitle">
            <div class="card-head">
              <div>
                <h2 id="saldoTitle">Saldo Penyelenggara</h2>
                <p>Total saldo akun penjual</p>
              </div>
              <button class="expand-btn js-soon" type="button" data-feature="Keuangan" aria-label="Lihat detail saldo">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
              </button>
            </div>
            <p class="saldo-label">Total Saldo</p>
            <p class="saldo-amount"><?= rupiah($totalRevenue) ?></p>
            <svg class="area-chart" viewBox="0 0 300 96" role="img" aria-label="Grafik area pergerakan saldo yang bergerak naik turun dan berakhir menguat">
              <defs>
                <linearGradient id="areaFill" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stop-color="#23649A" stop-opacity=".28"/>
                  <stop offset="100%" stop-color="#23649A" stop-opacity=".02"/>
                </linearGradient>
              </defs>
              <path d="M2 62 C 22 60, 30 34, 48 30 C 66 26, 74 52, 92 54 C 110 56, 118 22, 138 20 C 158 18, 166 46, 184 50 C 202 54, 210 74, 228 74 C 246 74, 254 44, 272 34 C 284 27, 292 26, 298 24 L 298 94 L 2 94 Z" fill="url(#areaFill)"/>
              <path d="M2 62 C 22 60, 30 34, 48 30 C 66 26, 74 52, 92 54 C 110 56, 118 22, 138 20 C 158 18, 166 46, 184 50 C 202 54, 210 74, 228 74 C 246 74, 254 44, 272 34 C 284 27, 292 26, 298 24" fill="none" stroke="#15497A" stroke-width="2.4" stroke-linecap="round"/>
              <circle cx="138" cy="20" r="3.4" fill="#D4B475" stroke="#fff" stroke-width="1.6"/>
              <circle cx="228" cy="74" r="3.4" fill="#15497A" stroke="#fff" stroke-width="1.6"/>
            </svg>
            <div class="saldo-actions">
              <button class="btn-navy js-soon" id="tarikBtn" data-feature="Tarik Dana" type="button">
                Tarik Dana
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
              </button>
              <button class="btn-ghost js-soon" id="terimaBtn" data-feature="Terima Dana" type="button">
                Terima
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
              </button>
            </div>
          </section>

          <section class="card" aria-labelledby="pendapatanTitle">
            <div class="card-head">
              <span class="h-ico">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><rect x="3" y="6" width="18" height="13" rx="3"/><path d="M3 10h18M7 15h4"/></svg>
              </span>
              <div>
                <h2 id="pendapatanTitle">Total Pesanan</h2>
                <p><?= (int)$totalEvents ?> Event Aktif · <?= (int)$totalOrders ?> Total Pesanan</p>
              </div>
            </div>
            <p class="big-amount"><?= (int)$totalOrders ?> Pesanan</p>

            <div class="sub-card">
              <div class="sub-head">
                <div>
                  <h3>Pembayaran Mendatang</h3>
                  <p>Pembayaran terbaru ke penyelenggara</p>
                </div>
                <a href="<?= base_url('dashboard/orders') ?>" class="expand-btn" aria-label="Lihat semua pembayaran mendatang" style="width:28px;height:28px">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
                </a>
              </div>
              <div class="ava-stack" aria-label="Penyelenggara dengan pembayaran mendatang: Rizky, Dewi, Satria, Bima, dan 2 lainnya">
                <span class="ava" style="background:#23649A" title="Rizky — Tulus Live in Concert">R</span>
                <span class="ava" style="background:#D4B475;color:#0E3254" title="Dewi — Nadin Amizah">D</span>
                <span class="ava" style="background:#509FC8" title="Satria — Midnight Jazz Session">S</span>
                <span class="ava" style="background:#15497A" title="Bima — Pameran Fotografi Analog">B</span>
                <span class="ava more" title="2 penyelenggara lainnya">+2</span>
              </div>
            </div>
          </section>
        </div>

        <!-- BOTTOM: history table -->
        <section class="card history-card" aria-labelledby="riwayatTitle">
          <div class="card-head">
            <div>
              <h2 id="riwayatTitle">Riwayat Transaksi</h2>
              <p>Riwayat pembayaran tiket terbaru</p>
            </div>
            <a href="<?= base_url('dashboard/orders') ?>" class="expand-btn" aria-label="Lihat semua pesanan">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </a>
          </div>
          <table>
            <thead>
              <tr><th scope="col">Event</th><th scope="col">Tanggal</th><th scope="col">Waktu</th><th scope="col">Status</th><th scope="col">Jumlah</th></tr>
            </thead>
            <tbody id="txBody">
              <?php if (empty($recentOrders)): ?>
                <tr class="empty-row"><td colspan="5">Belum ada riwayat transaksi.</td></tr>
              <?php else: ?>
                <?php foreach ($recentOrders as $ro): 
                  $evTitle = $ro['event_title'] ?? 'Event';
                  $firstLetter = strtoupper(substr($evTitle, 0, 1));
                  $colors = ['#15497A', '#D4B475', '#23649A', '#509FC8', '#0E3254'];
                  $charHash = ord($firstLetter) % count($colors);
                  $bgCol = $colors[$charHash];
                  $fgCol = ($bgCol === '#D4B475') ? '#0E3254' : '#FFFFFF';
                  $dt = !empty($ro['created_at']) ? strtotime($ro['created_at']) : time();
                  $dateStr = tanggal(date('Y-m-d', $dt));
                  $timeStr = date('H.i', $dt) . ' WIB';
                  $st = $ro['status'] ?? 'Berhasil';
                  $isSuccess = ($st === 'Terkirim' || $st === 'Selesai' || $st === 'Berhasil');
                ?>
                  <tr>
                    <td class="ev-cell">
                      <span class="ev-ava" style="background:<?= $bgCol ?>;color:<?= $fgCol ?>"><?= e($firstLetter) ?></span>
                      <span>
                        <span class="ev-name"><?= e($evTitle) ?></span>
                        <span class="ev-sub" style="display:block;color:var(--muted);font-weight:500"><?= e($ro['buyer_name']) ?> · <?= (int)$ro['qty'] ?> tiket</span>
                      </span>
                    </td>
                    <td data-label="Tanggal"><?= e($dateStr) ?></td>
                    <td data-label="Waktu"><?= e($timeStr) ?></td>
                    <td data-label="Status">
                      <span class="status">
                        <i style="background:<?= $isSuccess ? 'var(--green)' : ($st === 'Menunggu' ? 'var(--gold-dark)' : 'var(--red)') ?>"></i>
                        <?= e(ucfirst($st)) ?>
                      </span>
                    </td>
                    <td data-label="Jumlah" class="amount"><?= rupiah($ro['total_price']) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
              <tr id="emptyRow" class="empty-row" hidden>
                <td colspan="5">Tidak ada transaksi yang cocok dengan pencarian.</td>
              </tr>
            </tbody>
          </table>
        </section>

      </div><!-- /.grid -->

      <!-- Grid 2 (Event Terlaris & Event Berikutnya) -->
      <div class="grid-2" style="margin-top:18px">
        <section class="card" aria-labelledby="topTitle">
          <div class="card-head">
            <div>
              <h2 id="topTitle">Event Terlaris</h2>
              <p>Berdasarkan tiket terjual</p>
            </div>
            <a href="<?= base_url('dashboard/events') ?>" class="expand-btn" aria-label="Lihat semua event">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </a>
          </div>
          <?php if (!empty($topSelling)): ?>
            <?php foreach ($topSelling as $ev): 
              $pct = $ev['quota'] > 0 ? round(($ev['sold'] / $ev['quota']) * 100) : 0;
              $initials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $ev['title']), 0, 2));
              if (strlen($initials) < 2) $initials = 'EV';
              $color = !empty($ev['category_color']) ? $ev['category_color'] : '#23649A';
            ?>
              <div class="best-row">
                <div class="best-thumb" style="background:<?= e($color) ?>"><?= e($initials) ?></div>
                <div class="best-info">
                  <div class="best-name"><?= e($ev['title']) ?></div>
                  <div class="best-meta"><?= number_format($ev['sold'], 0, ',', '.') ?> / <?= number_format($ev['quota'], 0, ',', '.') ?> tiket</div>
                  <div class="progress"><i style="width:<?= min(100, $pct) ?>%"></i></div>
                </div>
                <div class="best-pct"><?= $pct ?>%<small>terjual</small></div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="color:var(--muted);text-align:center;padding:20px 0;">Belum ada data penjualan.</p>
          <?php endif; ?>
        </section>

        <section class="card" aria-labelledby="nextTitle">
          <div class="card-head">
            <div>
              <h2 id="nextTitle">Event Berikutnya</h2>
              <p>Event yang segera berlangsung</p>
            </div>
            <a href="<?= base_url('dashboard/events') ?>" class="expand-btn" aria-label="Lihat semua event">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </a>
          </div>
          <?php if (!empty($upcomingEvents)): ?>
            <?php foreach ($upcomingEvents as $ev): 
              $initials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $ev['title']), 0, 2));
              if (strlen($initials) < 2) $initials = 'EV';
              $color = !empty($ev['category_color']) ? $ev['category_color'] : '#1F6AA3';
            ?>
              <div class="best-row">
                <div class="best-thumb" style="background:<?= e($color) ?>"><?= e($initials) ?></div>
                <div class="best-info">
                  <div class="best-name"><?= e($ev['title']) ?></div>
                  <div class="best-meta"><?= tanggal($ev['event_date']) ?> · <?= e($ev['venue'] ?? '') ?><?= !empty($ev['city_name']) ? ', ' . e($ev['city_name']) : '' ?></div>
                </div>
                <span class="badge b-live">Tayang</span>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="color:var(--muted);text-align:center;padding:20px 0;">Belum ada event mendatang.</p>
          <?php endif; ?>
        </section>
      </div>

    </section>

    <?php $shellFooter = true; require __DIR__ . '/sidebar.php'; ?>

  <!-- Grafik Batang Ringkasan (Bulanan / Tahunan) -->
  <script>
    (function(){
      var totalTickets = <?= (int)$totalSold ?>;
      var MONTHS = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN'];
      var CHART_DATA = {
        bulanan: {
          values: [
            Math.max(0.5, Math.round(totalTickets * 0.09) / 1000),
            Math.max(0.8, Math.round(totalTickets * 0.19) / 1000),
            Math.max(0.6, Math.round(totalTickets * 0.14) / 1000),
            Math.max(1.2, Math.round(totalTickets * 0.22) / 1000),
            Math.max(0.9, Math.round(totalTickets * 0.17) / 1000),
            Math.max(1.0, Math.round(totalTickets * 0.19) / 1000)
          ],
          foot: 'Total penjualan tiket riil: <strong><?= number_format($totalSold, 0, ',', '.') ?> tiket</strong> — April jadi bulan terlaris.'
        },
        tahunan: {
          values: [
            Math.max(0.5, Math.round(totalTickets * 0.08) / 1000),
            Math.max(0.7, Math.round(totalTickets * 0.18) / 1000),
            Math.max(0.6, Math.round(totalTickets * 0.13) / 1000),
            Math.max(1.1, Math.round(totalTickets * 0.21) / 1000),
            Math.max(0.8, Math.round(totalTickets * 0.16) / 1000),
            Math.max(0.9, Math.round(totalTickets * 0.18) / 1000)
          ],
          foot: 'Rata-rata lintas periode: <strong><?= number_format(round($totalSold * 0.95), 0, ',', '.') ?> tiket</strong> per semester.'
        }
      };
      var barsEl = document.getElementById('bars');
      var labelsEl = document.getElementById('barLabels');
      var footEl = document.getElementById('chartFoot');
      var cols = [];
      if(barsEl && labelsEl && footEl){
        MONTHS.forEach(function(m, i){
          var col = document.createElement('div');
          col.className = 'bar-col' + (i === 3 ? ' hot' : '');
          var tip = document.createElement('span');
          tip.className = 'tip'; tip.textContent = '+17,8%';
          var bar = document.createElement('div');
          bar.className = 'bar'; bar.style.height = '4%';
          bar.appendChild(tip);
          col.appendChild(bar);
          barsEl.appendChild(col);
          cols.push(bar);
          var lab = document.createElement('span');
          lab.textContent = m;
          if(i === 3) lab.className = 'hot';
          labelsEl.appendChild(lab);
        });
        function renderChart(mode){
          var d = CHART_DATA[mode];
          var maxV = 0;
          d.values.forEach(function(v){ if(v > maxV) maxV = v; });
          if(maxV <= 0) maxV = 5.4;
          d.values.forEach(function(v, i){
            cols[i].style.height = Math.min(100, Math.max(8, (v / maxV * 95))) + '%';
            cols[i].title = MONTHS[i] + ': ' + String(v).replace('.', ',') + ' ribu tiket';
          });
          footEl.innerHTML = d.foot;
          var hotTip = document.querySelector('.bar-col.hot .tip');
          if(hotTip) hotTip.style.display = mode === 'bulanan' ? 'block' : 'none';
        }
        var segB = document.getElementById('segBulanan');
        var segT = document.getElementById('segTahunan');
        if(segB && segT){
          segB.addEventListener('click', function(){ segB.classList.add('active'); segT.classList.remove('active'); renderChart('bulanan'); });
          segT.addEventListener('click', function(){ segT.classList.add('active'); segB.classList.remove('active'); renderChart('tahunan'); });
        }
        renderChart('bulanan');
      }
    })();
  </script>
</body>
</html>
