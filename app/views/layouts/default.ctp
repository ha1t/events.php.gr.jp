<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<title>{if $app.site_title}{$app.site_title} - {/if}{$site_name}</title>
<?php echo $html->css('phpgrjp'); ?>
<link rel="alternate" type="application/rss+xml" title="RSS" href="{$BASE_URL}/rss" />
</head>
<body>
<div id="header">
  <h1 class="ja"><a href="http://www.php.gr.jp/" title="日本 PHP ユーザ会メインページへ">日本 PHP ユーザ会</a></h1>
  <h1 class="en"><a href="http://www.php.gr.jp/" title="日本 PHP ユーザ会メインページへ">Japan PHP Users Group</a></h1>
  <div id="phpug-logo">
    <a href="http://www.php.gr.jp/" title="日本 PHP ユーザ会メインページへ">
      <img src="http://www.php.gr.jp/images/h1.gif" width="179" height="19" alt="Japan PHP Users Group" />

    </a>
  </div>
  <div id="navigation">
    <ul>
      <li id="menu-item-www" class="menu-item"><a href="http://www.php.gr.jp/">メイン</a> </li>
      <li id="menu-item-news" class="menu-item"><a href="http://news.php.gr.jp/">ニュース</a> </li>
      <li id="menu-item-ml" class="menu-item"><a href="http://ml.php.gr.jp/">メーリングリスト</a> </li>

      <li id="menu-item-bbs" class="menu-item"><a href="http://bbs.php.gr.jp/">掲示板</a> </li>
      <li id="menu-item-planet" class="menu-item"><a href="http://planet.php.gr.jp/">プラネット</a> </li>
      <li id="menu-item-docs" class="menu-item"><a href="http://docs.php.gr.jp/">日本語ドキュメント</a> </li>
      <li id="menu-item-events" class="menu-item menu-point"><a href="http://events.php.gr.jp/">イベント</a> </li>
    </ul>

  </div>
  <div class="tail"></div>
</div>
<div id="container">
  <div id="top"><h1>events.php.gr.jp</h1></div>
  <div id="content">
  <div id="menu">
    <ul class="menu">
      <li><?php echo $html->link('Top', '/'); ?></li>
      <?php /* 最上位権限を持っていると表示される */ ?>
      {if $session.is_admin}
      <li><a href="{$BASE_URL}/news_admin">NewsAdmin</a></li>
      <li><a href="{$BASE_URL}/event_admin">EventAdmin</a></li>
      <li><a href="{$BASE_URL}/admin">Setting</a></li>
      {/if}
      <?php /* ログインしたユーザだけ表示される */ ?>
      {if isset($session.name)}
      <li><a href="{$BASE_URL}/logout">Logout</a></li>
      <li>USER:<em>{$session.name}</em></li>
      <li>Role:<em>{if $session.is_admin}Admin{else}User{/if}</em></li>
      {else}
      <li><a href="{$BASE_URL}/login">Login</a></li>
      {/if}
    </ul>
  </div>

<?php echo $content_for_layout; ?>

<br />
<hr />
</div>
<div id="footer">
&copy;&nbsp;EventSystem&nbsp;version&nbsp;<?php echo Configure::read('Event.version'); ?>
</div>
</div>
</body>
</html>
