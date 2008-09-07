<?php
/**
 * event_page.php
 *
 */

class EventPage extends AppModel
{
    var $name = 'EventPage';
    var $useTable = 'event_page';

    /**
     * afterFind
     *
     */
    function afterFind($result, $primary = null)
    {
        // WikiPage$B$N%l%s%@%j%s%0(B
        require_once APP . 'Text/PukiWiki.php';
        $pukiwiki = new Text_PukiWiki();

        foreach ($result as $key => $row) {
            if (isset($row['EventPage']['content'])) {
            $result[$key]['EventPage']['content'] = $pukiwiki->toHtml($row['EventPage']['content']);
            }
        }

        return $result;
    }
}
