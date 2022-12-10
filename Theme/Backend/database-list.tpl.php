<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Admin\Template\Backend
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/**
 * @var \phpOMS\Views\View            $this
 * @var \Modules\Admin\Models\query[] $queries
 */
$queries = $this->getData('queries') ?? [];

$previous = empty($querys) ? 'dbeditor/editor/list' : '{/lang}/{/app}/dbeditor/editor/list?{?}&id=' . \reset($querys)->getId() . '&ptype=p';
$next     = empty($querys) ? 'dbeditor/editor/list' : '{/lang}/{/app}/dbeditor/editor/list?{?}&id=' . \end($querys)->getId() . '&ptype=n';

echo $this->getData('nav')->render(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Querys'); ?><i class="fa fa-download floatRight download btn"></i></div>
            <div class="slider">
            <table id="queryList" class="default sticky">
                <thead>
                <tr>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                        <label for="queryList-sort-1">
                            <input type="radio" name="queryList-sort" id="queryList-sort-1">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="queryList-sort-2">
                            <input type="radio" name="queryList-sort" id="queryList-sort-2">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td class="wf-100"><?= $this->getHtml('Title'); ?>
                        <label for="queryList-sort-3">
                            <input type="radio" name="queryList-sort" id="queryList-sort-3">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="queryList-sort-4">
                            <input type="radio" name="queryList-sort" id="queryList-sort-4">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Creator'); ?>
                        <label for="queryList-sort-5">
                            <input type="radio" name="queryList-sort" id="queryList-sort-5">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="queryList-sort-6">
                            <input type="radio" name="queryList-sort" id="queryList-sort-6">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Created'); ?>
                        <label for="queryList-sort-7">
                            <input type="radio" name="queryList-sort" id="queryList-sort-7">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="queryList-sort-8">
                            <input type="radio" name="queryList-sort" id="queryList-sort-8">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <tbody>
                        <?php $c = 0; foreach ($queries as $key => $value) : ++$c;
                        $url     = \phpOMS\Uri\UriFactory::build('dbeditor/editor?{?}&id=' . $value->getId());
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
            </div>
            <div class="portlet-foot">
                <a tabindex="0" class="button" href="<?= UriFactory::build($previous); ?>"><?= $this->getHtml('Previous', '0', '0'); ?></a>
                <a tabindex="0" class="button" href="<?= UriFactory::build($next); ?>"><?= $this->getHtml('Next', '0', '0'); ?></a>
            </div>
        </div>
    </div>
</div>
