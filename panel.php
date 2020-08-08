<div class="panel">
    <a href="index.php<?= '?pref=' . $val['pref'] ?>">
        <span class="panel-pref <?= areaClassCalled($val['area']) ?>"><?= prefNameCalled($val['pref']) ?></span>
    </a>
    <a href="eventDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam() . '&e_id=' . $val['id'] : '?e_id=' . $val['id']; ?>">
        <div class="panel-body">
            <img src="<?php echo (!empty($val['pic1'])) ? sanitize($val['pic1']) : 'images/noimage2.png'; ?>" class="img -index">
            <p class="panel-title">
                <span class="panel-date"><?= date("Y年n月j日", strtotime($val['date_start'])) ?><?php if ($val['date_start'] !== $val['date_end']) {
                                                                                                    echo '〜' . date("n月j日", strtotime($val['date_end']));
                                                                                                } elseif ($val['date_start'] !== $val['date_end'] && date("Y", strtotime($val['date_start'])) !== date("Y", strtotime($val['dte_end']))) {
                                                                                                    echo '〜' . date("Y年n月j日", strtotime($val['date_end']));
                                                                                                } ?></span><br>
                <?= $val['name'] ?>
            </p>
        </div>
    </a>
</div>