<?php
    $Faqs = array();

    $Faqs[] = [
        'q' => 'Why should I use Quoootes?',
        'a' => 'Other online quote-maker tools aren\'t built for simplicity. <br> Quoootes, on the other hand, is built to create beautiful images with quotes, super simple and stuning fast.'
    ];

    $Faqs[] = [
        'q' => 'Can I make your my quotes?',
        'a' => 'Of course, you can <strong>make your own quotes</strong> by simply writing the text and picking a beautiful template.'
    ];

    $Faqs[] = [
        'q' => 'What types of quotes can I make with Quoootes?',
        'a' => 'We have ready-to-go templates to create love quotes, inspirational or motivational quotes, sad quotes, or any kind of topic.'
    ];

    $Faqs[] = [
        'q' => 'Is Quoootes free?',
        'a' => 'Pretty much! Our free plan lets you create unlimited quotes from tons of free templates, with the Quoootes watermark on the image. <br><br> ' .
               'Our Pro plan comes with premium templates, no watermark on designs, and the ability to add your custom logo to promote you or your business',
    ];

    $Faqs[] = [
        'q' => 'Do you need an account to use Quoootes editor?',
        'a' => 'Nope! You can create quotes, download and share it without creating any account.' .
               ' Anyway, if you choose to create an account, all the quotes will be saved on your dashboard.',
    ];
    $Faqs[] = [
        'q' => 'Do you have a desktop or mobile app?',
        'a' => 'No, we don\'t. Anyway, our online quote maker works in all the mobile and desktop browsers, just enter to <a href="//quoootes.com">quoootes.com</a> ;)',
    ];
    $Faqs[] = [
        'q' => 'Is Quoootes GDPR compliant?',
        'a' => 'we care a lot about privacy and we’re continuously striving to fulfill the GDPR. You can read all about our compliance efforts, privacy, and security in our GDPR Policy.',
    ];

    if (isset($data) && is_array($data) && count($data) > 0) {
        $Faqs = $data;
    }
?>

<div class="accordion w-100" id="basicAccordion" itemscope="" itemtype="https://schema.org/FAQPage">
    <?php for ($i=0; $i < count($Faqs); $i++): ?>
    <div class="accordion-item" itemscope="" itemprop="mainEntity" itemtype="https://schema.org/Question">
        <h3 class="accordion-header" id="headingOne" itemprop="name">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?=$i?>" aria-expanded="false" aria-controls="collapseOne">
                <?= $Faqs[$i]['q']; ?>
            </button>
        </h3>
        <div itemscope="" itemprop="acceptedAnswer" itemtype="https://schema.org/Answer" id="faq<?=$i?>" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#basicAccordion">
            <div class="accordion-body" itemprop="text"><?= $Faqs[$i]['a']; ?></div>
        </div>
    </div>
    <?php endfor; ?>
</div>