<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\DatabaseEditor
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\DatabaseEditor\Models\NullQuery;
use phpOMS\DataStorage\Database\DatabaseType;
use phpOMS\Utils\IO\Csv\CsvSettings;

$dbTypes = DatabaseType::getConstants();
$query   = $this->getData('query') ?? new NullQuery();

echo $this->data['nav']->render(); ?>

<div class="row">
    <div class="col-xs-12 col-md-3">
        <section class="portlet">
            <form id="fDatabaseConnection" method="GET" action="<?= \phpOMS\Uri\UriFactory::build('{/api}dbeditor/editor?{?}&csrf={$CSRF}'); ?>">
                <div class="portlet-body">
                    <table class="layout wf-100">
                        <tbody>
                        <tr><td><label for="iDatabaseType"><?= $this->getHtml('DatabaseType'); ?></label>
                        <tr><td>
                            <select id="iDatabaseType" name="type">
                                <?php foreach ($dbTypes as $type): ?>
                                <option value="<?= $this->printHtml($type); ?>"><?= $this->printHtml($type); ?>
                                <?php endforeach; ?>
                            </select>
                        <tr><td><label for="iHost"><?= $this->getHtml('Host'); ?></label>
                        <tr><td><input type="text" id="iHost" name="host" value="<?= $this->printHtml($query->host); ?>">
                        <tr><td><label for="iPort"><?= $this->getHtml('Port'); ?></label>
                        <tr><td><input min="0" max="65536" type="number" id="iPort" name="port" value="<?= $query->port; ?>">
                        <tr><td><label for="iDatabase"><?= $this->getHtml('Database'); ?></label>
                        <tr><td><input type="text" id="iDatabase" name="database" value="<?= $this->printHtml($query->db); ?>">
                        <tr><td><label for="iLogin"><?= $this->getHtml('Login'); ?></label>
                        <tr><td><input type="text" id="iLogin" name="login">
                        <tr><td><label for="iPassword"><?= $this->getHtml('Password'); ?></label>
                        <tr><td><input type="text" id="iPassword" name="password">
                    </table>
                </div>
                <div class="portlet-foot"><input type="submit" value="<?= $this->getHtml('Test'); ?>"></div>
            </form>
        </section>
    </div>

    <div class="col-xs-12 col-md-9">
        <section class="portlet">
            <div class="portlet-body">
                <table class="layout wf-100">
                    <tbody>
                    <tr><td><label for="iTitle"><?= $this->getHtml('Title'); ?></label>
                    <tr><td><input id="iTitle" type="text" value="<?= $this->printHtml($query->title); ?>">
                    <tr><td><label for="iQuery"><?= $this->getHtml('Query'); ?></label>
                    <tr><td><textarea id="iQuery" style="height: 300px" form="fDatabaseConnection"><?= $this->printHtml($query->query); ?></textarea>
                </table>
            </div>
            <div class="portlet-foot">
                <input form="fDatabaseConnection" type="submit" value="<?= $this->getHtml('Execute'); ?>">
            </div>
        </section>
    </div>
</div>

<div class="tabview tab-2">
    <div class="box">
        <ul class="tab-links">
            <li><label for="c-tab-1"><?= $this->getHtml('Query'); ?></label>
            <li><label for="c-tab-2"><?= $this->getHtml('Database'); ?></label>
        </ul>
    </div>
    <div class="tab-content">
        <input type="radio" id="c-tab-1" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-1' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12">
                    <section class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('QueryResult'); ?> - <?= $this->getHtml('Limit1000'); ?><i class="g-icon download btn end-xs">download</i></div>
                        <table class="default sticky">
                        <thead>
                        <tbody>
                            <?php if ($query->id !== 0) :
                                $delim = CsvSettings::getStringDelimiter($query->result, 3);
                                $lines = \explode("\n", $query->result);

                                foreach ($lines as $data) : $line = \str_getcsv($data, $delim, '"'); ?>
                                    <tr>
                                    <?php foreach ($line as $cell) : ?>
                                        <td><?= $this->printHtml($cell); ?>
                                    <?php endforeach; ?>
                            <?php endforeach; else : ?>
                                <tr><td><?= $this->getHtml('NoResults'); ?>
                            <?php endif; ?>
                        </table>
                    </section>
                </div>
            </div>
        </div>
        <input type="radio" id="c-tab-2" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-2' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-3">
                    <section class="box wf-100">
                        <div class="inner">
                        </div>
                    </section>
                </div>

                <div class="col-xs-9">
                    <section class="box wf-100">
                        <div class="inner">
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
