<?php
require 'db.php';
$faqs = $db->faq->find([]);
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title>FAQ</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5">
  <h2>FAQ</h2>
  <div class="accordion" id="faqAccordion">
  <?php $i=0; foreach ($faqs as $f): $i++; ?>
    <div class="accordion-item">
      <h2 class="accordion-header" id="heading<?=$i?>">
        <button class="accordion-button <?=$i>1?'collapsed':''?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$i?>">
          <?= htmlspecialchars($f['question']) ?>
        </button>
      </h2>
      <div id="collapse<?=$i?>" class="accordion-collapse collapse <?=$i==1?'show':''?>" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          <?= nl2br(htmlspecialchars($f['answer'])) ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
</div>
</body></html>