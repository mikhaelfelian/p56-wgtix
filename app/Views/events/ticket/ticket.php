<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Ticket Card</title>
  <style>
    .cardWrap {
      width: 27em;
      display: inline-block;
      /* biar bisa sejajar kalau dicetak banyak */
      margin: 2mm;
      /* jarak tipis antar tiket untuk digunting */
      color: #fff;
      font-family: sans-serif;
      vertical-align: top;
      /* sejajarkan atas */
    }

    .card {
      background: linear-gradient(to bottom, #e84c3d 0%, #e84c3d 26%, #ecedef 26%, #ecedef 100%);
      height: 11em;
      float: left;
      position: relative;
      padding: 1em;
      margin-top: 0;
      /* hilangkan jarak besar */
    }

    .cardLeft {
      border-top-left-radius: 8px;
      border-bottom-left-radius: 8px;
      width: 16em;
    }

    .cardRight {
      width: 6.5em;
      border-left: .18em dashed #fff;
      border-top-right-radius: 8px;
      border-bottom-right-radius: 8px;
      position: relative;
      text-align: center;
    }

    /* lubang sobekan */
    .cardRight:before,
    .cardRight:after {
      content: "";
      position: absolute;
      display: block;
      width: .9em;
      height: .9em;
      background: #fff;
      border-radius: 50%;
      left: -.5em;
    }

    .cardRight:before {
      top: -.4em;
    }

    .cardRight:after {
      bottom: -.4em;
    }

    h1 {
      font-size: 1.1em;
      margin-top: 0;
    }

    h1 span {
      font-weight: normal;
    }

    .title,
    .name,
    .seat,
    .time {
      font-weight: normal;
    }

    .title h2,
    .name h2,
    .seat h2,
    .time h2 {
      font-size: .9em;
      color: #525252;
      margin: 0;
    }

    .title span,
    .name span,
    .seat span,
    .time span {
      font-size: .7em;
      color: #a2aeae;
    }

    .title {
      margin: 2em 0 0 0;
    }

    .name,
    .seat {
      margin: .7em 0 0 0;
    }

    .time {
      margin: .7em 0 0 1em;
    }

    .seat,
    .time {
      float: left;
    }

    /* “eye” merah di stub */
    .eye {
      position: relative;
      width: 2em;
      height: 1.5em;
      background: #fff;
      margin: 0.6em auto 0.4em;
      /* sedikit jarak supaya QR muat di bawahnya */
      border-radius: 1em/0.6em;
      z-index: 1;
    }

    .eye:before,
    .eye:after {
      content: "";
      display: block;
      position: absolute;
      border-radius: 50%;
    }

    .eye:before {
      width: 1em;
      height: 1em;
      background: #e84c3d;
      z-index: 2;
      left: 8px;
      top: 4px;
    }

    .eye:after {
      width: .5em;
      height: .5em;
      background: #fff;
      z-index: 3;
      left: 12px;
      top: 8px;
    }

    /* QR di stub kanan */
    .qr-box {
      display: block;
      margin: 0.2em auto 0;
      padding: 0 0.4em;
    }

    .qr-box img {
      display: block;
      max-width: 100%;
      width: 80px;
      /* atur ukuran QR di sini */
      height: 80px;
      /* persegi */
      object-fit: contain;
      margin: 0 auto;
      background: #fff;
      /* biar kontras */
      border-radius: 4px;
    }

    /* (opsional) label kecil di bawah QR */
    .qr-label {
      font-size: 0.65em;
      color: #a2aeae;
      text-transform: uppercase;
      margin-top: .2em;
    }

    @media print {
      * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
    }
  </style>
</head>

<body>
  <?php foreach ($items as $item): ?>
    <?php $itemData = json_decode($item->item_data, true); ?>
    <div class="cardWrap">
      <div class="card cardLeft">
        <h1><?= esc($item->event_title) ?></h1>
        <div class="title">
          <h2><?= esc($item->price_description) ?></h2>
          <span>Rp. <?= format_angka($item->unit_price) ?></span>
        </div>
        <div class="name">
          <h2><?= esc($itemData['participant_name']) ?></h2>
          <span>name</span>
        </div>
        <div class="seat">
          <h2>156</h2>
          <span>seat</span>
        </div>
        <div class="time">
          <h2>12:00</h2>
          <span>time</span>
        </div>
      </div>
      <div class="card cardRight">
        <div class="eye"></div>
        <div class="qr-box">
          <!-- Ganti src berikut dengan URL/file QR-mu atau data URI -->
          <img src="<?= base_url('file/sale/' . $item->id_penjualan . '/qrcode/' . $item->qrcode) ?>" alt="QR <?= esc($item->id) ?>">
          <div class="qr-label">scan</div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</body>

</html>