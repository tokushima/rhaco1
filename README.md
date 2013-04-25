rhaco1
======
#使い方
<http://gihyo.jp/dev/serial/01/rhaco>



#Conveyor(Prhagger)

##インストール
		Conveyorを<https://github.com/tokushima/rhaco1/blob/master/repository/app/org.rhaco.conveyor.tgz>からダウンロード、解凍します。
		rhacoを<https://github.com/tokushima/rhaco1/blob/master/bin/rhaco1.tgz>からダウンロードして解凍します。
		解凍したrhacoフォルダをConveyorのlibrary以下へ配置します。

##初期処理
		ブラウザからsetup.phpを実行するとセットアップ画面が表示します
		[settings] settingボタン押下します。

	
##Lineの作成
		ブラウザからConveyor(index.php)を実行するとLine生成のUIが表示されます。
		LineフォームのNameにLineの名前を入力します。(今回はfeed)
		右のWorkersリストからFeedInを左のWorkerフォームへドラッグします。
		右のWorkersリストからHtmlOutを左のWorkerフォームへドラッグします。
		追加したWorkerフォームFeedInのConfigs内「RSSのURL」にフィードを含むURLを入力します。
		Generateボタンを押下します。

###フィードを含むURLの例
		http://labs.unoh.net/
		http://b.hatena.ne.jp/hotentry

##Lineの実行
		setup.phpの[settings]アプリケーションデータの設定 &gt; 実行ファイルの出力場所 で指定されたパスに<br />
		LineフォームのNameで指定した名前 + .phpのファイル名でLineファイルが出力されています。<br />

		> ~/publish/feed.php
