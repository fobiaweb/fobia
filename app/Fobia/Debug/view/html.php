<?php
/**
 * html.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
?>
<style>
    #ac-logger-switch {
        position: fixed;
        background: #FF0000;
        top: 5px;
        right: 5px;
        width: 30px;
        height: 15px;
        z-index: 1000001;
        cursor: pointer;
    }
    #ac-logger {
        position: fixed;
        top: 0px;
        left: 0px;
        z-index: 1000000;
        font: 9px Tahoma, Geneva, sans-serif;
        border-bottom: 3px solid black;
        height: 60%;
        width: 100%;
        display: none;
    }
    #ac-logger div.content {
        background: #CCC;
        overflow: auto;
        width: 100%;
        height: 100%;
    }
    #ac-logger table tr {
        font-family: monospace;
        font-size: 11px;
    }
    #ac-logger thead {
        background: #666;
    }
    #ac-logger thead tr th {
        vertical-align: top;
        white-space: pre;
        font-weight: bold;
        text-align: left;
    }
    #ac-logger thead tr td {
        vertical-align: top;
        text-align: left;
    }
    #ac-logger tr .number {
        color: #888a85;
    }
    #ac-logger tr .time {
        color: #f57900;
    }
    #ac-logger tr .category {
        color: #4e9a06;
    }
    #ac-logger tr .level {
        color: #578ed5;
        /* color: #3465a4; */
    }
    #ac-logger tr .messag {
        color: #888a85;
    }
    #ac-logger tr.error {
        background: #ffb3b3;
    }
    #ac-logger tr.warning {
        background-color: #e9d5ab;
    }
    #ac-logger tr.dump {
        /* background-color: rgb(163, 163, 163); */
    }
    .debug-cvardumper {}
    .debug-cvardumper .string { color: #C00; }
    .debug-cvardumper .comment { color: #888A85; }
    .debug-cvardumper .keyword { color: #4E9A06; }
    .debug-cvardumper .default { color: #3465A4; }
    .debug-cvardumper .html {}
</style>


<div id="ac-logger-switch">DBG</div>
<div id="ac-logger" class="">
    <div class="content">
        <table style="width: 100%;">
            <thead>
                <tr >
                    <th class="number"   style="width: 30px;">№</th>
                    <th class="time"     style="width: 70px;">time    </th>
                    <th class="level"    style="width: 85px;">level   </th>
                    <th class="message">message</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->list as $row): ?>
                <tr>
                    <td class="number"><?= sprintf("%'02d", ++ $i); ?></td>
                    <td class="time"><?= sprintf("%-9s", $row['time']); ?></td>
                    <td class="level"><?= sprintf("%-9s", "[{$row['level']}]"); ?></td>
                    <td class="message"><?= $row['message'] . $row['context']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
<?php 
echo file_get_contents(__DIR__ . '/debug.js');
?>

</script>
