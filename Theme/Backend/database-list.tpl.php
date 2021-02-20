<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\Admin\Template\Backend
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/**
 * @var \phpOMS\Views\View            $this
 * @var \Modules\Admin\Models\query[] $queries
 */
$queries = $this->getData('queries') ?? [];

$previous = empty($querys) ? '{/prefix}dbeditor/editor/list' : '{/prefix}dbeditor/editor/list?{?}&id=' . \reset($querys)->getId() . '&ptype=p';
$next     = empty($querys) ? '{/prefix}dbeditor/editor/list' : '{/prefix}dbeditor/editor/list?{?}&id=' . \end($querys)->getId() . '&ptype=n';

echo $this->getData('nav')->render(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Querys'); ?><i class="fa fa-download floatRight download btn"></i></div>
            <table id="queryList" class="default">
                <thead>
                <tr>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                        <input id="queryList-r1-asc" name="queryList-sort" type="radio"><label for="queryList-r1-asc"><i class="sort-asc fa fa-chevron-up"></i></label>
                        <input id="queryList-r1-desc" name="queryList-sort" type="radio"><label for="queryList-r1-desc"><i class="sort-desc fa fa-chevron-down"></i></label>
                    <td class="wf-100"><?= $this->getHtml('Title'); ?>
                        <input id="queryList-r2-asc" name="queryList-sort" type="radio"><label for="queryList-r2-asc"><i class="sort-asc fa fa-chevron-up"></i></label>
                        <input id="queryList-r2-desc" name="queryList-sort" type="radio"><label for="queryList-r2-desc"><i class="sort-desc fa fa-chevron-down"></i></label>
                    <td><?= $this->getHtml('Creator'); ?>
                        <input id="queryList-r4-asc" name="queryList-sort" type="radio"><label for="queryList-r4-asc"><i class="sort-asc fa fa-chevron-up"></i></label>
                        <input id="queryList-r4-desc" name="queryList-sort" type="radio"><label for="queryList-r4-desc"><i class="sort-desc fa fa-chevron-down"></i></label>
                    <td><?= $this->getHtml('Created'); ?>
                        <input id="queryList-r5-asc" name="queryList-sort" type="radio"><label for="queryList-r5-asc"><i class="sort-asc fa fa-chevron-up"></i></label>
                        <input id="queryList-r5-desc" name="queryList-sort" type="radio"><label for="queryList-r5-desc"><i class="sort-desc fa fa-chevron-down"></i></label>
                    <tbody>
                        <?php $c = 0; foreach ($queries as $key => $value) : ++$c;
                        $url     = \phpOMS\Uri\UriFactory::build('{/prefix}dbeditor/editor?{?}&id=' . $value->getId());
                        ?>
                <tr tabindex="0" data-href="<?= $url; ?>">
                    <td data-label="<?= $this->getHtml('ID', '0', '0'); ?>"><a href="<?= $url; ?>"><?= $value->getId(); ?></a>
                    <td data-label="<?= $this->getHtml('Title'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->title); ?></a>
                    <td data-label="<?= $this->getHtml('Creator'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml(
                                \sprintf('%3$s %2$s %1$s', $value->createdBy->name1, $value->createdBy->name2, $value->createdBy->name3)
                            ); ?></a>
                    <td data-label="<?= $this->getHtml('Created'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->createdAt->format('Y-m-d H:i:s')); ?></a>
                        <?php endforeach; ?>
                        <?php if ($c === 0) : ?>
                            <tr><td colspan="4" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                        <?php endif; ?>
            </table>
            <div class="portlet-foot">
                <a tabindex="0" class="button" href="<?= UriFactory::build($previous); ?>"><?= $this->getHtml('Previous', '0', '0'); ?></a>
                <a tabindex="0" class="button" href="<?= UriFactory::build($next); ?>"><?= $this->getHtml('Next', '0', '0'); ?></a>
            </div>
        </div>
    </div>
</div>
