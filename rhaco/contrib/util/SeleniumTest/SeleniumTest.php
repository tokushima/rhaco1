<?php
Rhaco::import("util.UnitTest");
Rhaco::import("network.http.Browser");
/**
 * Selenium RC driver for rhaco UnitTest
 * @author SHIGETA Takeshiro
 * @license New BSD License
 * @copyright Copyright 2008 rhaco project. All rights reserved.
 *　このソフトウェアは，Testing__Selenium version 0.4.3 を改変して作成したもの
 *　である．オリジナルの Testing__Selenium の著作権表示・再頒布条件・無保証
 *　規定は以下の通り．
 *
 *  Copyright 2006 ThoughtWorks, Inc
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *	 http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * -----------------
 * This file has been automatically generated via XSL
 * -----------------
 *
 *
 *
 * @category   Testing
 * @package	Selenium
 * @author	 Shin Ohno <ganchiku at gmail dot com>
 * @author	 Bjoern Schotte <schotte at mayflower dot de>
 * @license	http://www.apache.org/licenses/LICENSE-2.0  Apache License, Version 2.0
 * @version	0.4.3
 * @see		http://www.openqa.org/selenium-rc/
 * @since	  0.1
 */
class SeleniumTest extends UnitTest
{
	var $browser;
	var $browserName = "*firefox";
	var $hostUrl = "http://localhost:4444/selenium-server/driver/";
	var $targetUrl;
	var $sessionId;
	var $timeout = 30000;

	function SeleniumTest(){
		$args = func_get_args();
		$this->__init__($args);
	}
	function __init__($args=null){
		$this->browser = new Browser();
		parent::UnitTest($args[0]);
	}
	function setBrowserName($name){
		$this->browserName = $name;
	}
	function setTargetUrl($url){
		$this->targetUrl = $url;
	}
	function setHost($host){
		$this->hostUrl = sprintf('http://%s/selenium-server/driver/',$host);
	}
	function start(){
		$this->sessionId = $this->_string("getNewBrowserSession", array($this->browserName,$this->targetUrl));
		return $this->sessionId;
	}
	function stop()
	{
		$this->_command("testComplete");
		$this->sessionId = null;
	}
	/**
	 * クリック
	 * Clicks on a link, button, checkbox or radio button. If the click action
	 * causes a new page to load (like a link usually does), call
	 * waitForPageToLoad.
	 *
	 * @access public
	 * @param string $locator an element locator
	 */
	function click($locator)
	{
		$this->_command("click", array($locator));
	}
	/**
	 * ダブルクリック
	 * Double clicks on a link, button, checkbox or radio button. If the double click action
	 * causes a new page to load (like a link usually does), call
	 * waitForPageToLoad.
	 *
	 * @access public
	 * @param string $locator an element locator
	 */
	function doubleClick($locator)
	{
		$this->_command("doubleClick", array($locator));
	}
	/**
	 * 右クリック？（コンテキストメニュー）
	 * Simulates opening the context menu for the specified element (as might happen if the user "right-clicked" on the element).
	 *
	 * @access public
	 * @param string $locator an element locator
	 */
	function contextMenu($locator)
	{
		$this->_command("contextMenu", array($locator));
	}
	/**
	 * クリック（locatorからの相対位置指定）
	 * Clicks on a link, button, checkbox or radio button. If the click action
	 * causes a new page to load (like a link usually does), call
	 * waitForPageToLoad.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $coordString specifies the x,y position (i.e. - 10,20) of the mouse	  event relative to the element returned by the locator.
	 */
	function clickAt($locator, $coordString)
	{
		$this->_command("clickAt", array($locator, $coordString));
	}
	/**
	 * ダブルクリック（locatorからの相対位置指定）
	 * Doubleclicks on a link, button, checkbox or radio button. If the action
	 * causes a new page to load (like a link usually does), call
	 * waitForPageToLoad.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $coordString specifies the x,y position (i.e. - 10,20) of the mouse	  event relative to the element returned by the locator.
	 */
	function doubleClickAt($locator, $coordString)
	{
		$this->_command("doubleClickAt", array($locator, $coordString));
	}
	/**
	 * 右クリック？（コンテキストメニュー）（locatorからの相対位置指定）
	 * Simulates opening the context menu for the specified element (as might happen if the user "right-clicked" on the element).
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $coordString specifies the x,y position (i.e. - 10,20) of the mouse	  event relative to the element returned by the locator.
	 */
	function contextMenuAt($locator, $coordString)
	{
		$this->_command("contextMenuAt", array($locator, $coordString));
	}
	/**
	 * イベント発生
	 * Explicitly simulate an event, to trigger the corresponding "on<i>event</i>"
	 * handler.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $eventName the event name, e.g. "focus" or "blur"
	 */
	function fireEvent($locator, $eventName)
	{
		$this->_command("fireEvent", array($locator, $eventName));
	}
	/**
	 * フォーカス
	 * Move the focus to the specified element; for example, if the element is an input field, move the cursor to that field.
	 *
	 * @access public
	 * @param string $locator an element locator
	 */
	function focus($locator)
	{
		$this->_command("focus", array($locator));
	}
	/**
	 * キーを押して放す
	 * Simulates a user pressing and releasing a key.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $keySequence Either be a string("\" followed by the numeric keycode  of the key to be pressed, normally the ASCII value of that key), or a single  character. For example: "w", "\119".
	 */
	function keyPress($locator, $keySequence)
	{
		$this->_command("keyPress", array($locator, $keySequence));
	}
	/**
	 * シフトキーを押す（doShiftUpを呼ぶかページ移動するまで押される）
	 * Press the shift key and hold it down until doShiftUp() is called or a new page is loaded.
	 *
	 * @access public
	 */
	function shiftKeyDown()
	{
		$this->_command("shiftKeyDown");
	}
	/**
	 * シフトキーを放す
	 * Release the shift key.
	 *
	 * @access public
	 */
	function shiftKeyUp()
	{
		$this->_command("shiftKeyUp");
	}
	/**
	 * metaキーを押す（metaKeyUpを呼ぶかページ移動するまで押される）
	 * Press the meta key and hold it down until doMetaUp() is called or a new page is loaded.
	 *
	 * @access public
	 */
	function metaKeyDown()
	{
		$this->_command("metaKeyDown");
	}
	/**
	 * metaキーを放す
	 * Release the meta key.
	 *
	 * @access public
	 */
	function metaKeyUp()
	{
		$this->_command("metaKeyUp", array());
	}
	/**
	 * altキーを押す（altKeyUpを呼ぶかページ移動するまで押される）
	 * Press the alt key and hold it down until doAltUp() is called or a new page is loaded.
	 *
	 * @access public
	 */
	function altKeyDown()
	{
		$this->_command("altKeyDown", array());
	}
	/**
	 * altキーを放す
	 * Release the alt key.
	 *
	 * @access public
	 */
	function altKeyUp()
	{
		$this->_command("altKeyUp", array());
	}
	/**
	 * ctrlキーを押す（controlKeyUpを呼ぶかページ移動するまで押される）
	 * Press the control key and hold it down until doControlUp() is called or a new page is loaded.
	 *
	 * @access public
	 */
	function controlKeyDown()
	{
		$this->_command("controlKeyDown", array());
	}
	/**
	 * ctrlキーを放す
	 * Release the control key.
	 *
	 * @access public
	 */
	function controlKeyUp()
	{
		$this->_command("controlKeyUp", array());
	}
	/**
	 * 何かのキーを押す（文字を指定するかASCIIキーコードを指定）
	 * Simulates a user pressing a key (without releasing it yet).
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $keySequence Either be a string("\" followed by the numeric keycode  of the key to be pressed, normally the ASCII value of that key), or a single  character. For example: "w", "\119".
	 */
	function keyDown($locator, $keySequence)
	{
		$this->_command("keyDown", array($locator, $keySequence));
	}
	/**
	 * 何かのキーを放す
	 * Simulates a user releasing a key.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $keySequence Either be a string("\" followed by the numeric keycode  of the key to be pressed, normally the ASCII value of that key), or a single  character. For example: "w", "\119".
	 */
	function keyUp($locator, $keySequence)
	{
		$this->_command("keyUp", array($locator, $keySequence));
	}
	/**
	 * マウスオーバーする
	 * Simulates a user hovering a mouse over the specified element.
	 *
	 * @access public
	 * @param string $locator an element locator
	 */
	function mouseOver($locator)
	{
		$this->_command("mouseOver", array($locator));
	}
	/**
	 * マウスアウトする
	 * Simulates a user moving the mouse pointer away from the specified element.
	 *
	 * @access public
	 * @param string $locator an element locator
	 */
	function mouseOut($locator)
	{
		$this->_command("mouseOut", array($locator));
	}
	/**
	 * 左マウスダウン
	 * Simulates a user pressing the left mouse button (without releasing it yet) on
	 * the specified element.
	 *
	 * @access public
	 * @param string $locator an element locator
	 */
	function mouseDown($locator)
	{
		$this->_command("mouseDown", array($locator));
	}
	/**
	 * 右マウスダウン
	 * Simulates a user pressing the right mouse button (without releasing it yet) on
	 * the specified element.
	 *
	 * @access public
	 * @param string $locator an element locator
	 */
	function mouseDownRight($locator)
	{
		$this->_command("mouseDownRight", array($locator));
	}
	/**
	 * 位置指定左マウスダウン
	 * Simulates a user pressing the left mouse button (without releasing it yet) at
	 * the specified location.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $coordString specifies the x,y position (i.e. - 10,20) of the mouse	  event relative to the element returned by the locator.
	 */
	function mouseDownAt($locator, $coordString)
	{
		$this->_command("mouseDownAt", array($locator, $coordString));
	}
	/**
	 * 位置指定右マウスダウン
	 * Simulates a user pressing the right mouse button (without releasing it yet) at
	 * the specified location.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $coordString specifies the x,y position (i.e. - 10,20) of the mouse	  event relative to the element returned by the locator.
	 */
	function mouseDownRightAt($locator, $coordString)
	{
		$this->_command("mouseDownRightAt", array($locator, $coordString));
	}
	/**
	 * 左マウスアップ
	 * Simulates the event that occurs when the user releases the mouse button (i.e., stops
	 * holding the button down) on the specified element.
	 *
	 * @access public
	 * @param string $locator an element locator
	 */
	function mouseUp($locator)
	{
		$this->_command("mouseUp", array($locator));
	}
	/**
	 * 右マウスアップ
	 * Simulates the event that occurs when the user releases the right mouse button (i.e., stops
	 * holding the button down) on the specified element.
	 *
	 * @access public
	 * @param string $locator an element locator
	 */
	function mouseUpRight($locator)
	{
		$this->_command("mouseUpRight", array($locator));
	}
	/**
	 * 位置指定左マウスアップ
	 * Simulates the event that occurs when the user releases the mouse button (i.e., stops
	 * holding the button down) at the specified location.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $coordString specifies the x,y position (i.e. - 10,20) of the mouse	  event relative to the element returned by the locator.
	 */
	function mouseUpAt($locator, $coordString)
	{
		$this->_command("mouseUpAt", array($locator, $coordString));
	}
	/**
	 * 位置指定右マウスアップ
	 * Simulates the event that occurs when the user releases the right mouse button (i.e., stops
	 * holding the button down) at the specified location.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $coordString specifies the x,y position (i.e. - 10,20) of the mouse	  event relative to the element returned by the locator.
	 */
	function mouseUpRightAt($locator, $coordString)
	{
		$this->_command("mouseUpRightAt", array($locator, $coordString));
	}
	/**
	 * マウス移動
	 * Simulates a user pressing the mouse button (without releasing it yet) on
	 * the specified element.
	 *
	 * @access public
	 * @param string $locator an element locator
	 */
	function mouseMove($locator)
	{
		$this->_command("mouseMove", array($locator));
	}
	/**
	 * 指定位置でのマウス移動
	 * Simulates a user pressing the mouse button (without releasing it yet) on
	 * the specified element.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $coordString specifies the x,y position (i.e. - 10,20) of the mouse	  event relative to the element returned by the locator.
	 */
	function mouseMoveAt($locator, $coordString)
	{
		$this->_command("mouseMoveAt", array($locator, $coordString));
	}
	/**
	 * フィールドへの入力
	 * Sets the value of an input field, as though you typed it in.
	 *
	 * <p>
	 * Can also be used to set the value of combo boxes, check boxes, etc. In these cases,
	 * value should be the value of the option selected, not the visible text.
	 * </p>
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $value the value to type
	 */
	function type($locator, $value)
	{
		$this->_command("type", array($locator, $value));
	}
	/**
	 * フィールドへ入力してキーダウン、アップ系イベントを発生する
	 * Simulates keystroke events on the specified element, as though you typed the value key-by-key.
	 *
	 * <p>
	 * This is a convenience method for calling keyDown, keyUp, keyPress for every character in the specified string;
	 * this is useful for dynamic UI widgets (like auto-completing combo boxes) that require explicit key events.
	 * </p><p>
	 * Unlike the simple "type" command, which forces the specified value into the page directly, this command
	 * may or may not have any visible effect, even in cases where typing keys would normally have a visible effect.
	 * For example, if you use "typeKeys" on a form element, you may or may not see the results of what you typed in
	 * the field.
	 * </p><p>
	 * In some cases, you may need to use the simple "type" command to set the value of the field and then the "typeKeys" command to
	 * send the keystroke events corresponding to what you just typed.
	 * </p>
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $value the value to type
	 */
	function typeKeys($locator, $value)
	{
		$this->_command("typeKeys", array($locator, $value));
	}
	/**
	 * 実行速度を指定する
	 * Set execution speed (i.e., set the millisecond length of a delay which will follow each selenium operation).  By default, there is no such delay, i.e.,
	 * the delay is 0 milliseconds.
	 *
	 * @access public
	 * @param string $value the number of milliseconds to pause after operation
	 */
	function setSpeed($value)
	{
		$this->_command("setSpeed", array($value));
	}
	/**
	 * 実行速度を取得する
	 * Get execution speed (i.e., get the millisecond length of the delay following each selenium operation).  By default, there is no such delay, i.e.,
	 * the delay is 0 milliseconds.
	 *
	 * See also setSpeed.
	 *
	 * @access public
	 * @return string the execution speed in milliseconds.
	 */
	function getSpeed()
	{
		return $this->_string("getSpeed", array());
	}
	/**
	 * チェックボックスやラジオボタンをチェックする
	 * Check a toggle-button (checkbox/radio)
	 *
	 * @access public
	 * @param string $locator an element locator
	 */
	function check($locator)
	{
		$this->_command("check", array($locator));
	}
	/**
	 * チェックボックスやラジオボタンのチェックを解除する
	 * Uncheck a toggle-button (checkbox/radio)
	 *
	 * @access public
	 * @param string $locator an element locator
	 */
	function uncheck($locator)
	{
		$this->_command("uncheck", array($locator));
	}
	/**
	 * ドロップダウンリストから値を選択する
	 * Select an option from a drop-down using an option locator.
	 *
	 * <p>
	 *
	 * Option locators provide different ways of specifying options of an HTML
	 * Select element (e.g. for selecting a specific option, or for asserting
	 * that the selected option satisfies a specification). There are several
	 * forms of Select Option Locator.
	 *
	 * </p>
	 * <ul>
	 *
	 * <li>
	 * <b>label</b>=<i>labelPattern</i>:
	 * matches options based on their labels, i.e. the visible text. (This
	 * is the default.)
	 *
	 * <ul>
	 *
	 * <li>
	 * label=regexp:^[Oo]ther
	 * </li>
	 * </ul>
	 * </li>
	 * <li>
	 * <b>value</b>=<i>valuePattern</i>:
	 * matches options based on their values.
	 *
	 * <ul>
	 *
	 * <li>
	 * value=other
	 * </li>
	 * </ul>
	 * </li>
	 * <li>
	 * <b>id</b>=<i>id</i>:
	 *
	 * matches options based on their ids.
	 *
	 * <ul>
	 *
	 * <li>
	 * id=option1
	 * </li>
	 * </ul>
	 * </li>
	 * <li>
	 * <b>index</b>=<i>index</i>:
	 * matches an option based on its index (offset from zero).
	 *
	 * <ul>
	 *
	 * <li>
	 * index=2
	 * </li>
	 * </ul>
	 * </li>
	 * </ul><p>
	 *
	 * If no option locator prefix is provided, the default behaviour is to match on <b>label</b>.
	 *
	 * </p>
	 *
	 * @access public
	 * @param string $selectLocator an element locator identifying a drop-down menu
	 * @param string $optionLocator an option locator (a label by default)
	 */
	function select($selectLocator, $optionLocator)
	{
		$this->_command("select", array($selectLocator, $optionLocator));
	}
	/**
	 * 複数選択可能なドロップダウンリストで選択を追加する
	 * Add a selection to the set of selected options in a multi-select element using an option locator.
	 *
	 * @see #doSelect for details of option locators
	 *
	 * @access public
	 * @param string $locator an element locator identifying a multi-select box
	 * @param string $optionLocator an option locator (a label by default)
	 */
	function addSelection($locator, $optionLocator)
	{
		$this->_command("addSelection", array($locator, $optionLocator));
	}
	/**
	 * 複数選択可能なドロップダウンリストで選択を削除する
	 * Remove a selection from the set of selected options in a multi-select element using an option locator.
	 *
	 * @see #doSelect for details of option locators
	 *
	 * @access public
	 * @param string $locator an element locator identifying a multi-select box
	 * @param string $optionLocator an option locator (a label by default)
	 */
	function removeSelection($locator, $optionLocator)
	{
		$this->_command("removeSelection", array($locator, $optionLocator));
	}
	/**
	 * 複数選択可能なドロップダウンリストで全ての選択を削除する
	 * Unselects all of the selected options in a multi-select element.
	 *
	 * @access public
	 * @param string $locator an element locator identifying a multi-select box
	 */
	function removeAllSelections($locator)
	{
		$this->_command("removeAllSelections", array($locator));
	}
	/**
	 * サブミットする
	 * Submit the specified form. This is particularly useful for forms without
	 * submit buttons, e.g. single-input "Search" forms.
	 *
	 * @access public
	 * @param string $formLocator an element locator for the form you want to submit
	 */
	function submit($formLocator)
	{
		$this->_command("submit", array($formLocator));
	}
	/**
	 * ページを開く
	 * Opens an URL in the test frame. This accepts both relative and absolute
	 * URLs.
	 *
	 * The "open" command waits for the page to load before proceeding,
	 * ie. the "AndWait" suffix is implicit.
	 *
	 * <i>Note</i>: The URL must be on the same domain as the runner HTML
	 * due to security restrictions in the browser (Same Origin Policy). If you
	 * need to open an URL on another domain, use the Selenium Server to start a
	 * new browser session on that domain.
	 *
	 * @access public
	 * @param string $url the URL to open; may be relative or absolute
	 */
	function open($url)
	{
		$this->_command("open", array($url));
	}
	/**
	 * ポップアップウィンドウを開く
	 * Opens a popup window (if a window with that ID isn't already open).
	 * After opening the window, you'll need to select it using the selectWindow
	 * command.
	 *
	 * <p>
	 * This command can also be a useful workaround for bug SEL-339.  In some cases, Selenium will be unable to intercept a call to window.open (if the call occurs during or before the "onLoad" event, for example).
	 * In those cases, you can force Selenium to notice the open window's name by using the Selenium openWindow command, using
	 * an empty (blank) url, like this: openWindow("", "myFunnyWindow").
	 * </p>
	 *
	 * @access public
	 * @param string $url the URL to open, which can be blank
	 * @param string $windowID the JavaScript window ID of the window to select
	 */
	function openWindow($url, $windowID)
	{
		$this->_command("openWindow", array($url, $windowID));
	}
	/**
	 * ポップアップウィンドウを選択する
	 * Selects a popup window using a window locator; once a popup window has been selected, all
	 * commands go to that window. To select the main window again, use null
	 * as the target.
	 *
	 * <p>
	 *
	 *
	 * Window locators provide different ways of specifying the window object:
	 * by title, by internal JavaScript "name," or by JavaScript variable.
	 *
	 * </p>
	 * <ul>
	 *
	 * <li>
	 * <b>title</b>=<i>My Special Window</i>:
	 * Finds the window using the text that appears in the title bar.  Be careful;
	 * two windows can share the same title.  If that happens, this locator will
	 * just pick one.
	 *
	 * </li>
	 * <li>
	 * <b>name</b>=<i>myWindow</i>:
	 * Finds the window using its internal JavaScript "name" property.  This is the second
	 * parameter "windowName" passed to the JavaScript method window.open(url, windowName, windowFeatures, replaceFlag)
	 * (which Selenium intercepts).
	 *
	 * </li>
	 * <li>
	 * <b>var</b>=<i>variableName</i>:
	 * Some pop-up windows are unnamed (anonymous), but are associated with a JavaScript variable name in the current
	 * application window, e.g. "window.foo = window.open(url);".  In those cases, you can open the window using
	 * "var=foo".
	 *
	 * </li>
	 * </ul><p>
	 *
	 * If no window locator prefix is provided, we'll try to guess what you mean like this:
	 * </p><p>
	 * 1.) if windowID is null, (or the string "null") then it is assumed the user is referring to the original window instantiated by the browser).
	 * </p><p>
	 * 2.) if the value of the "windowID" parameter is a JavaScript variable name in the current application window, then it is assumed
	 * that this variable contains the return value from a call to the JavaScript window.open() method.
	 * </p><p>
	 * 3.) Otherwise, selenium looks in a hash it maintains that maps string names to window "names".
	 * </p><p>
	 * 4.) If <i>that</i> fails, we'll try looping over all of the known windows to try to find the appropriate "title".
	 * Since "title" is not necessarily unique, this may have unexpected behavior.
	 * </p><p>
	 * If you're having trouble figuring out the name of a window that you want to manipulate, look at the Selenium log messages
	 * which identify the names of windows created via window.open (and therefore intercepted by Selenium).  You will see messages
	 * like the following for each window as it is opened:
	 * </p><p>
	 * <code>debug: window.open call intercepted; window ID (which you can use with selectWindow()) is "myNewWindow"</code>
	 * </p><p>
	 * In some cases, Selenium will be unable to intercept a call to window.open (if the call occurs during or before the "onLoad" event, for example).
	 * (This is bug SEL-339.)  In those cases, you can force Selenium to notice the open window's name by using the Selenium openWindow command, using
	 * an empty (blank) url, like this: openWindow("", "myFunnyWindow").
	 * </p>
	 *
	 * @access public
	 * @param string $windowID the JavaScript window ID of the window to select
	 */
	function selectWindow($windowID)
	{
		$this->_command("selectWindow", array($windowID));
	}
	/**
	 * 選択されたウィンドウ内でフレームを選択する
	 * Selects a frame within the current window.  (You may invoke this command
	 * multiple times to select nested frames.)  To select the parent frame, use
	 * "relative=parent" as a locator; to select the top frame, use "relative=top".
	 * You can also select a frame by its 0-based index number; select the first frame with
	 * "index=0", or the third frame with "index=2".
	 *
	 * <p>
	 * You may also use a DOM expression to identify the frame you want directly,
	 * like this: <code>dom=frames["main"].frames["subframe"]</code>
	 * </p>
	 *
	 * @access public
	 * @param string $locator an element locator identifying a frame or iframe
	 */
	function selectFrame($locator)
	{
		$this->_command("selectFrame", array($locator));
	}
	/**
	 * 指定したフレームが今動いてるコードで作られたものか調べる
	 * Determine whether current/locator identify the frame containing this running code.
	 *
	 * <p>
	 * This is useful in proxy injection mode, where this code runs in every
	 * browser frame and window, and sometimes the selenium server needs to identify
	 * the "current" frame.  In this case, when the test calls selectFrame, this
	 * routine is called for each frame to figure out which one has been selected.
	 * The selected frame will return true, while all others will return false.
	 * </p>
	 *
	 * @access public
	 * @param string $currentFrameString starting frame
	 * @param string $target new frame (which might be relative to the current one)
	 * @return boolean true if the new frame is this code's window
	 */
	function getWhetherThisFrameMatchFrameExpression($currentFrameString, $target)
	{
		return $this->_bool("getWhetherThisFrameMatchFrameExpression", array($currentFrameString, $target));
	}
	/**
	 * 指定したウィンドウが今動いてるコードで作られたものか調べる
	 * Determine whether currentWindowString plus target identify the window containing this running code.
	 *
	 * <p>
	 * This is useful in proxy injection mode, where this code runs in every
	 * browser frame and window, and sometimes the selenium server needs to identify
	 * the "current" window.  In this case, when the test calls selectWindow, this
	 * routine is called for each window to figure out which one has been selected.
	 * The selected window will return true, while all others will return false.
	 * </p>
	 *
	 * @access public
	 * @param string $currentWindowString starting window
	 * @param string $target new window (which might be relative to the current one, e.g., "_parent")
	 * @return boolean true if the new window is this code's window
	 */
	function getWhetherThisWindowMatchWindowExpression($currentWindowString, $target)
	{
		return $this->_bool("getWhetherThisWindowMatchWindowExpression", array($currentWindowString, $target));
	}
	/**
	 * ポップアップウィンドウが出るまで待つ
	 * Waits for a popup window to appear and load up.
	 *
	 * @access public
	 * @param string $windowID the JavaScript window "name" of the window that will appear (not the text of the title bar)
	 * @param string $timeout a timeout in milliseconds, after which the action will return with an error
	 */
	function waitForPopUp($windowID, $timeout=null)
	{
		if(is_null($timeout)) $timeout = $this->timeout;
		$this->_command("waitForPopUp", array($windowID, $timeout));
	}
	/**
	 * javascriptのwindow.confirmでキャンセルを選択する
	 * <p>
	 *
	 * By default, Selenium's overridden window.confirm() function will
	 * return true, as if the user had manually clicked OK; after running
	 * this command, the next call to confirm() will return false, as if
	 * the user had clicked Cancel.  Selenium will then resume using the
	 * default behavior for future confirmations, automatically returning
	 * true (OK) unless/until you explicitly call this command for each
	 * confirmation.
	 *
	 * </p><p>
	 *
	 * Take note - every time a confirmation comes up, you must
	 * consume it with a corresponding getConfirmation, or else
	 * the next selenium operation will fail.
	 *
	 * </p>
	 *
	 * @access public
	 */
	function chooseCancelOnNextConfirmation()
	{
		$this->_command("chooseCancelOnNextConfirmation", array());
	}
	/**
	 * javascriptのwindow.confirmでOKを選択する（通常は必要ない）
	 * <p>
	 *
	 * Undo the effect of calling chooseCancelOnNextConfirmation.  Note
	 * that Selenium's overridden window.confirm() function will normally automatically
	 * return true, as if the user had manually clicked OK, so you shouldn't
	 * need to use this command unless for some reason you need to change
	 * your mind prior to the next confirmation.  After any confirmation, Selenium will resume using the
	 * default behavior for future confirmations, automatically returning
	 * true (OK) unless/until you explicitly call chooseCancelOnNextConfirmation for each
	 * confirmation.
	 *
	 * </p><p>
	 *
	 * Take note - every time a confirmation comes up, you must
	 * consume it with a corresponding getConfirmation, or else
	 * the next selenium operation will fail.
	 *
	 * </p>
	 *
	 * @access public
	 */
	function chooseOkOnNextConfirmation()
	{
		$this->_command("chooseOkOnNextConfirmation", array());
	}
	/**
	 * javascriptプロンプトに何か入力する
	 * Instructs Selenium to return the specified answer string in response to
	 * the next JavaScript prompt [window.prompt()].
	 *
	 * @access public
	 * @param string $answer the answer to give in response to the prompt pop-up
	 */
	function answerOnNextPrompt($answer)
	{
		$this->_command("answerOnNextPrompt", array($answer));
	}
	/**
	 * ブラウザの戻るボタンをクリック
	 * Simulates the user clicking the "back" button on their browser.
	 *
	 * @access public
	 */
	function goBack()
	{
		$this->_command("goBack", array());
	}
	/**
	 * ブラウザの更新ボタンをクリック
	 * Simulates the user clicking the "Refresh" button on their browser.
	 *
	 * @access public
	 */
	function refresh()
	{
		$this->_command("refresh", array());
	}
	/**
	 * ポップアップウィンドウやタブのの閉じるボタンをクリック
	 * Simulates the user clicking the "close" button in the titlebar of a popup
	 * window or tab.
	 *
	 * @access public
	 */
	function close()
	{
		$this->_command("close", array());
	}
	/**
	 * アラートが出たか調べる
	 * Has an alert occurred?
	 *
	 * <p>
	 *
	 * This function never throws an exception
	 *
	 * </p>
	 *
	 * @access public
	 * @return boolean true if there is an alert
	 */
	function isAlertPresent()
	{
		return $this->_bool("isAlertPresent", array());
	}
	/**
	 * プロンプトが出たか調べる
	 * Has a prompt occurred?
	 *
	 * <p>
	 *
	 * This function never throws an exception
	 *
	 * </p>
	 *
	 * @access public
	 * @return boolean true if there is a pending prompt
	 */
	function isPromptPresent()
	{
		return $this->_bool("isPromptPresent");
	}
	/**
	 * window.confirmが呼ばれたか調べる
	 * Has confirm() been called?
	 *
	 * <p>
	 *
	 * This function never throws an exception
	 *
	 * </p>
	 *
	 * @access public
	 * @return boolean true if there is a pending confirmation
	 */
	function isConfirmationPresent()
	{
		return $this->_bool("isConfirmationPresent");
	}
	/**
	 * アラートの内容を取得する
	 * Retrieves the message of a JavaScript alert generated during the previous action, or fail if there were no alerts.
	 *
	 * <p>
	 * Getting an alert has the same effect as manually clicking OK. If an
	 * alert is generated but you do not consume it with getAlert, the next Selenium action
	 * will fail.
	 * </p><p>
	 * Under Selenium, JavaScript alerts will NOT pop up a visible alert
	 * dialog.
	 * </p><p>
	 * Selenium does NOT support JavaScript alerts that are generated in a
	 * page's onload() event handler. In this case a visible dialog WILL be
	 * generated and Selenium will hang until someone manually clicks OK.
	 * </p>
	 *
	 * @access public
	 * @return string The message of the most recent JavaScript alert
	 */
	function getAlert()
	{
		return $this->_string("getAlert");
	}
	/**
	 * javascriptのwindow.confirmメッセージを取得する
	 * Retrieves the message of a JavaScript confirmation dialog generated during
	 * the previous action.
	 * <p>
	 *
	 * By default, the confirm function will return true, having the same effect
	 * as manually clicking OK. This can be changed by prior execution of the
	 * chooseCancelOnNextConfirmation command.
	 *
	 * </p><p>
	 *
	 * If an confirmation is generated but you do not consume it with getConfirmation,
	 * the next Selenium action will fail.
	 *
	 * </p><p>
	 *
	 * NOTE: under Selenium, JavaScript confirmations will NOT pop up a visible
	 * dialog.
	 *
	 * </p><p>
	 *
	 * NOTE: Selenium does NOT support JavaScript confirmations that are
	 * generated in a page's onload() event handler. In this case a visible
	 * dialog WILL be generated and Selenium will hang until you manually click
	 * OK.
	 *
	 * </p>
	 *
	 * @access public
	 * @return string the message of the most recent JavaScript confirmation dialog
	 */
	function getConfirmation()
	{
		return $this->_string("getConfirmation");
	}
	/**
	 * javascriptのプロンプトメッセージを取得
	 * Retrieves the message of a JavaScript question prompt dialog generated during
	 * the previous action.
	 *
	 * <p>
	 * Successful handling of the prompt requires prior execution of the
	 * answerOnNextPrompt command. If a prompt is generated but you
	 * do not get/verify it, the next Selenium action will fail.
	 * </p><p>
	 * NOTE: under Selenium, JavaScript prompts will NOT pop up a visible
	 * dialog.
	 * </p><p>
	 * NOTE: Selenium does NOT support JavaScript prompts that are generated in a
	 * page's onload() event handler. In this case a visible dialog WILL be
	 * generated and Selenium will hang until someone manually clicks OK.
	 * </p>
	 *
	 * @access public
	 * @return string the message of the most recent JavaScript question prompt
	 */
	function getPrompt()
	{
		return $this->_string("getPrompt");
	}
	/**
	 * 現在のページの絶対URLを取得する
	 * Gets the absolute URL of the current page.
	 *
	 * @access public
	 * @return string the absolute URL of the current page
	 */
	function getLocation()
	{
		return $this->_string("getLocation");
	}
	/**
	 * 現在のページのタイトルを取得する
	 * Gets the title of the current page.
	 *
	 * @access public
	 * @return string the title of the current page
	 */
	function getTitle()
	{
		return $this->_string("getTitle");
	}
	/**
	 * 現在のページの全テキストを取得する
	 * Gets the entire text of the page.
	 *
	 * @access public
	 * @return string the entire text of the page
	 */
	function getBodyText()
	{
		return $this->_string("getBodyText", array());
	}
	/**
	 * 入力フィールドの値を取得する（空白は削除）
	 * Gets the (whitespace-trimmed) value of an input field (or anything else with a value parameter).
	 * For checkbox/radio elements, the value will be "on" or "off" depending on
	 * whether the element is checked or not.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @return string the element value, or "on/off" for checkbox/radio elements
	 */
	function getValue($locator)
	{
		return $this->_string("getValue", array($locator));
	}
	/**
	 * 要素のテキストを取得する
	 * Gets the text of an element. This works for any element that contains
	 * text. This command uses either the textContent (Mozilla-like browsers) or
	 * the innerText (IE-like browsers) of the element, which is the rendered
	 * text shown to the user.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @return string the text of the element
	 */
	function getText($locator)
	{
		return $this->_string("getText", array($locator));
	}
	/**
	 * 要素の背景を黄色にする（ハイライト）
	 * Briefly changes the backgroundColor of the specified element yellow.  Useful for debugging.
	 *
	 * @access public
	 * @param string $locator an element locator
	 */
	function highlight($locator)
	{
		$this->_command("highlight", array($locator));
	}
	/**
	 * javascriptの内容をevalする
	 * Gets the result of evaluating the specified JavaScript snippet.  The snippet may
	 * have multiple lines, but only the result of the last line will be returned.
	 *
	 * <p>
	 * Note that, by default, the snippet will run in the context of the "selenium"
	 * object itself, so <code>this</code> will refer to the Selenium object.  Use <code>window</code> to
	 * refer to the window of your application, e.g. <code>window.document.getElementById('foo')</code>
	 * </p><p>
	 * If you need to use
	 * a locator to refer to a single element in your application page, you can
	 * use <code>this.browserbot.findElement("id=foo")</code> where "id=foo" is your locator.
	 * </p>
	 *
	 * @access public
	 * @param string $script the JavaScript snippet to run
	 * @return string the results of evaluating the snippet
	 */
	function getEval($script)
	{
		return $this->_string("getEval", array($script));
	}
	/**
	 * ラジオボタン、チェックボックスがチェックされているか調べる
	 * Gets whether a toggle-button (checkbox/radio) is checked.  Fails if the specified element doesn't exist or isn't a toggle-button.
	 *
	 * @access public
	 * @param string $locator an element locator pointing to a checkbox or radio button
	 * @return boolean true if the checkbox is checked, false otherwise
	 */
	function isChecked($locator)
	{
		return $this->_bool("isChecked", array($locator));
	}
	/**
	 * テーブルのセルからテキストを取得する
	 * Gets the text from a cell of a table. The cellAddress syntax
	 * tableLocator.row.column, where row and column start at 0.
	 *
	 * @access public
	 * @param string $tableCellAddress a cell address, e.g. "foo.1.4"
	 * @return string the text from the specified cell
	 */
	function getTable($tableCellAddress)
	{
		return $this->_string("getTable", array($tableCellAddress));
	}
	/**
	 * 複数選択可のドロップダウンリストで選択したラベルを全て取得する
	 * Gets all option labels (visible text) for selected options in the specified select or multi-select element.
	 *
	 * @access public
	 * @param string $selectLocator an element locator identifying a drop-down menu
	 * @return array an array of all selected option labels in the specified select drop-down
	 */
	function getSelectedLabels($selectLocator)
	{
		return $this->_stringArray("getSelectedLabels", array($selectLocator));
	}
	/**
	 * ドロップダウンリストで選択したラベルを取得する
	 * Gets option label (visible text) for selected option in the specified select element.
	 *
	 * @access public
	 * @param string $selectLocator an element locator identifying a drop-down menu
	 * @return string the selected option label in the specified select drop-down
	 */
	function getSelectedLabel($selectLocator)
	{
		return $this->_string("getSelectedLabel", array($selectLocator));
	}
	/**
	 * 複数選択可のドロップダウンリストで選択した値を全て取得する
	 * Gets all option values (value attributes) for selected options in the specified select or multi-select element.
	 *
	 * @access public
	 * @param string $selectLocator an element locator identifying a drop-down menu
	 * @return array an array of all selected option values in the specified select drop-down
	 */
	function getSelectedValues($selectLocator)
	{
		return $this->_stringArray("getSelectedValues", array($selectLocator));
	}
	/**
	 * ドロップダウンリストで選択した値を取得する
	 * Gets option value (value attribute) for selected option in the specified select element.
	 *
	 * @access public
	 * @param string $selectLocator an element locator identifying a drop-down menu
	 * @return string the selected option value in the specified select drop-down
	 */
	function getSelectedValue($selectLocator)
	{
		return $this->_string("getSelectedValue", array($selectLocator));
	}
	/**
	 * 複数選択可のドロップダウンリストで選択した番号(index)を全て取得する
	 * Gets all option indexes (option number, starting at 0) for selected options in the specified select or multi-select element.
	 *
	 * @access public
	 * @param string $selectLocator an element locator identifying a drop-down menu
	 * @return array an array of all selected option indexes in the specified select drop-down
	 */
	function getSelectedIndexes($selectLocator)
	{
		return $this->_stringArray("getSelectedIndexes", array($selectLocator));
	}
	/**
	 * ドロップダウンリストで選択した番号(index)を取得する
	 * Gets option index (option number, starting at 0) for selected option in the specified select element.
	 *
	 * @access public
	 * @param string $selectLocator an element locator identifying a drop-down menu
	 * @return string the selected option index in the specified select drop-down
	 */
	function getSelectedIndex($selectLocator)
	{
		return $this->_string("getSelectedIndex", array($selectLocator));
	}
	/**
	 * 複数選択可のドロップダウンリストで選択したidを全て取得する
	 * Gets all option element IDs for selected options in the specified select or multi-select element.
	 *
	 * @access public
	 * @param string $selectLocator an element locator identifying a drop-down menu
	 * @return array an array of all selected option IDs in the specified select drop-down
	 */
	function getSelectedIds($selectLocator)
	{
		return $this->_stringArray("getSelectedIds", array($selectLocator));
	}
	/**
	 * ドロップダウンリストで選択したidを取得する
	 * Gets option element ID for selected option in the specified select element.
	 *
	 * @access public
	 * @param string $selectLocator an element locator identifying a drop-down menu
	 * @return string the selected option ID in the specified select drop-down
	 */
	function getSelectedId($selectLocator)
	{
		return $this->_string("getSelectedId", array($selectLocator));
	}
	/**
	 * 何か選択されているか調べる
	 * Determines whether some option in a drop-down menu is selected.
	 *
	 * @access public
	 * @param string $selectLocator an element locator identifying a drop-down menu
	 * @return boolean true if some option has been selected, false otherwise
	 */
	function isSomethingSelected($selectLocator)
	{
		return $this->_bool("isSomethingSelected", array($selectLocator));
	}
	/**
	 * ドロップダウンリストの全てのラベルを取得する
	 * Gets all option labels in the specified select drop-down.
	 *
	 * @access public
	 * @param string $selectLocator an element locator identifying a drop-down menu
	 * @return array an array of all option labels in the specified select drop-down
	 */
	function getSelectOptions($selectLocator)
	{
		return $this->_stringArray("getSelectOptions", array($selectLocator));
	}
	/**
	 * 要素のattributeを取得する
	 * Gets the value of an element attribute. The value of the attribute may
	 * differ across browsers (this is the case for the "style" attribute, for
	 * example).
	 *
	 * @access public
	 * @param string $attributeLocator an element locator followed by an @ sign and then the name of the attribute, e.g. "foo@bar"
	 * @return string the value of the specified attribute
	 */
	function getAttribute($attributeLocator)
	{
		return $this->_string("getAttribute", array($attributeLocator));
	}
	/**
	 * 指定したパターンのテキストが存在するか調べる
	 * Verifies that the specified text pattern appears somewhere on the rendered page shown to the user.
	 *
	 * @access public
	 * @param string $pattern a pattern to match with the text of the page
	 * @return boolean true if the pattern matches the text, false otherwise
	 */
	function isTextPresent($pattern)
	{
		return $this->_bool("isTextPresent", array($pattern));
	}
	/**
	 * 指定した要素がページに存在するか調べる
	 * Verifies that the specified element is somewhere on the page.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @return boolean true if the element is present, false otherwise
	 */
	function isElementPresent($locator)
	{
		return $this->_bool("isElementPresent", array($locator));
	}
	/**
	 * 指定した要素が可視か調べる
	 * Determines if the specified element is visible. An
	 * element can be rendered invisible by setting the CSS "visibility"
	 * property to "hidden", or the "display" property to "none", either for the
	 * element itself or one if its ancestors.  This method will fail if
	 * the element is not present.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @return boolean true if the specified element is visible, false otherwise
	 */
	function isVisible($locator)
	{
		return $this->_bool("isVisible", array($locator));
	}
	/**
	 * 指定した要素が編集可能か調べる
	 * Determines whether the specified input element is editable, ie hasn't been disabled.
	 * This method will fail if the specified element isn't an input element.
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @return boolean true if the input element is editable, false otherwise
	 */
	function isEditable($locator)
	{
		return $this->_bool("isEditable", array($locator));
	}
	/**
	 * 全てのボタンのIDを取得する
	 * Returns the IDs of all buttons on the page.
	 *
	 * <p>
	 * If a given button has no ID, it will appear as "" in this array.
	 * </p>
	 *
	 * @access public
	 * @return array the IDs of all buttons on the page
	 */
	function getAllButtons()
	{
		return $this->_stringArray("getAllButtons", array());
	}
	/**
	 * 全てのリンクのIDを取得する
	 * Returns the IDs of all links on the page.
	 *
	 * <p>
	 * If a given link has no ID, it will appear as "" in this array.
	 * </p>
	 *
	 * @access public
	 * @return array the IDs of all links on the page
	 */
	function getAllLinks()
	{
		return $this->_stringArray("getAllLinks", array());
	}
	/**
	 * 全ての入力フィールドのIDを取得する
	 * Returns the IDs of all input fields on the page.
	 *
	 * <p>
	 * If a given field has no ID, it will appear as "" in this array.
	 * </p>
	 *
	 * @access public
	 * @return array the IDs of all field on the page
	 */
	function getAllFields()
	{
		return $this->_stringArray("getAllFields", array());
	}
	/**
	 * 全てのウィンドウから指定したattributeの値を取得する
	 * Returns every instance of some attribute from all known windows.
	 *
	 * @access public
	 * @param string $attributeName name of an attribute on the windows
	 * @return array the set of values of this attribute from all known windows.
	 */
	function getAttributeFromAllWindows($attributeName)
	{
		return $this->_stringArray("getAttributeFromAllWindows", array($attributeName));
	}
	/**
	 * マウス速度の設定
	 * Configure the number of pixels between "mousemove" events during dragAndDrop commands (default=10).
	 * <p>
	 * Setting this value to 0 means that we'll send a "mousemove" event to every single pixel
	 * in between the start location and the end location; that can be very slow, and may
	 * cause some browsers to force the JavaScript to timeout.
	 * </p><p>
	 * If the mouse speed is greater than the distance between the two dragged objects, we'll
	 * just send one "mousemove" at the start location and then one final one at the end location.
	 * </p>
	 *
	 * @access public
	 * @param string $pixels the number of pixels between "mousemove" events
	 */
	function setMouseSpeed($pixels)
	{
		$this->_command("setMouseSpeed", array($pixels));
	}
	/**
	 * マウス速度の取得
	 * Returns the number of pixels between "mousemove" events during dragAndDrop commands (default=10).
	 *
	 * @access public
	 * @return number the number of pixels between "mousemove" events during dragAndDrop commands (default=10)
	 */
	function getMouseSpeed()
	{
		return $this->_number("getMouseSpeed", array());
	}
	/**
	 * 要素のドラッグアンドドロップ（指定位置へ）
	 * Drags an element a certain distance and then drops it
	 *
	 * @access public
	 * @param string $locator an element locator
	 * @param string $movementsString offset in pixels from the current location to which the element should be moved, e.g., "+70,-300"
	 */
	function dragAndDrop($locator, $movementsString)
	{
		$this->_command("dragAndDrop", array($locator, $movementsString));
	}
	/**
	 * 要素のドラッグアンドドロップ（指定要素へ）
	 * Drags an element and drops it on another element
	 *
	 * @access public
	 * @param string $locatorOfObjectToBeDragged an element to be dragged
	 * @param string $locatorOfDragDestinationObject an element whose location (i.e., whose center-most pixel) will be the point where locatorOfObjectToBeDragged  is dropped
	 */
	function dragAndDropToObject($locatorOfObjectToBeDragged, $locatorOfDragDestinationObject)
	{
		$this->_command("dragAndDropToObject", array($locatorOfObjectToBeDragged, $locatorOfDragDestinationObject));
	}
	/**
	 * 選択したウィンドウにフォーカスする
	 * Gives focus to the currently selected window
	 *
	 * @access public
	 */
	function windowFocus()
	{
		$this->_command("windowFocus");
	}
	/**
	 * ウィンドウを最大表示にする
	 * Resize currently selected window to take up the entire screen
	 *
	 * @access public
	 */
	function windowMaximize()
	{
		$this->_command("windowMaximize", array());
	}
	/**
	 * 全てのwindow idを取得する
	 * Returns the IDs of all windows that the browser knows about.
	 *
	 * @access public
	 * @return array the IDs of all windows that the browser knows about.
	 */
	function getAllWindowIds()
	{
		return $this->_stringArray("getAllWindowIds", array());
	}
	/**
	 * 全てのwindow名を取得する
	 * Returns the names of all windows that the browser knows about.
	 *
	 * @access public
	 * @return array the names of all windows that the browser knows about.
	 */
	function getAllWindowNames()
	{
		return $this->_stringArray("getAllWindowNames", array());
	}
	/**
	 * 全てのウィンドウタイトルを取得する
	 * Returns the titles of all windows that the browser knows about.
	 *
	 * @access public
	 * @return array the titles of all windows that the browser knows about.
	 */
	function getAllWindowTitles()
	{
		return $this->_stringArray("getAllWindowTitles", array());
	}
	/**
	 * <html>タグ範囲のhtmlソースを取得する
	 * Returns the entire HTML source between the opening and
	 * closing "html" tags.
	 *
	 * @access public
	 * @return string the entire HTML source
	 */
	function getHtmlSource()
	{
		return $this->_string("getHtmlSource", array());
	}
	/**
	 * カーソル位置を指定する
	 * Moves the text cursor to the specified position in the given input element or textarea.
	 * This method will fail if the specified element isn't an input element or textarea.
	 *
	 * @access public
	 * @param string $locator an element locator pointing to an input element or textarea
	 * @param string $position the numerical position of the cursor in the field; position should be 0 to move the position to the beginning of the field.  You can also set the cursor to -1 to move it to the end of the field.
	 */
	function setCursorPosition($locator, $position)
	{
		$this->_command("setCursorPosition", array($locator, $position));
	}
	/**
	 * 親要素に対する指定要素の番号（index）を取得する
	 * Get the relative index of an element to its parent (starting from 0). The comment node and empty text node
	 * will be ignored.
	 *
	 * @access public
	 * @param string $locator an element locator pointing to an element
	 * @return number of relative index of the element to its parent (starting from 0)
	 */
	function getElementIndex($locator)
	{
		return $this->_number("getElementIndex", array($locator));
	}
	/**
	 * 2つの要素が同じ親を持ち、要素が順序通りであるか調べる
	 * Check if these two elements have same parent and are ordered siblings in the DOM. Two same elements will
	 * not be considered ordered.
	 *
	 * @access public
	 * @param string $locator1 an element locator pointing to the first element
	 * @param string $locator2 an element locator pointing to the second element
	 * @return boolean true if element1 is the previous sibling of element2, false otherwise
	 */
	function isOrdered($locator1, $locator2)
	{
		return $this->_bool("isOrdered", array($locator1, $locator2));
	}
	/**
	 * 要素の左からの水平位置を取得する
	 * Retrieves the horizontal position of an element
	 *
	 * @access public
	 * @param string $locator an element locator pointing to an element OR an element itself
	 * @return number of pixels from the edge of the frame.
	 */
	function getElementPositionLeft($locator)
	{
		return $this->_number("getElementPositionLeft", array($locator));
	}
	/**
	 * 要素の上からの垂直位置を取得する
	 * Retrieves the vertical position of an element
	 *
	 * @access public
	 * @param string $locator an element locator pointing to an element OR an element itself
	 * @return number of pixels from the edge of the frame.
	 */
	function getElementPositionTop($locator)
	{
		return $this->_number("getElementPositionTop", array($locator));
	}
	/**
	 * 要素の幅を取得する
	 * Retrieves the width of an element
	 *
	 * @access public
	 * @param string $locator an element locator pointing to an element
	 * @return number width of an element in pixels
	 */
	function getElementWidth($locator)
	{
		return $this->_number("getElementWidth", array($locator));
	}
	/**
	 * 要素の高さを取得する
	 * Retrieves the height of an element
	 *
	 * @access public
	 * @param string $locator an element locator pointing to an element
	 * @return number height of an element in pixels
	 */
	function getElementHeight($locator)
	{
		return $this->_number("getElementHeight", array($locator));
	}
	/**
	 * 入力フィールドのカーソル位置を取得する
	 * Retrieves the text cursor position in the given input element or textarea; beware, this may not work perfectly on all browsers.
	 *
	 * <p>
	 * Specifically, if the cursor/selection has been cleared by JavaScript, this command will tend to
	 * return the position of the last location of the cursor, even though the cursor is now gone from the page.  This is filed as SEL-243.
	 * </p>
	 * This method will fail if the specified element isn't an input element or textarea, or there is no cursor in the element.
	 *
	 * @access public
	 * @param string $locator an element locator pointing to an input element or textarea
	 * @return number the numerical position of the cursor in the field
	 */
	function getCursorPosition($locator)
	{
		return $this->_number("getCursorPosition", array($locator));
	}
	/**
	 * 指定した書式を返す？
	 * Returns the specified expression.
	 *
	 * <p>
	 * This is useful because of JavaScript preprocessing.
	 * It is used to generate commands like assertExpression and waitForExpression.
	 * </p>
	 *
	 * @access public
	 * @param string $expression the value to return
	 * @return string the value passed in
	 */
	function getExpression($expression)
	{
		return $this->_string("getExpression", array($expression));
	}
	/**
	 * Xpath式にマッチする要素の数を取得する
	 * Returns the number of nodes that match the specified xpath, eg. "//table" would give
	 * the number of tables.
	 *
	 * @access public
	 * @param string $xpath the xpath expression to evaluate. do NOT wrap this expression in a 'count()' function; we will do that for you.
	 * @return number the number of nodes that match the specified xpath
	 */
	function getXpathCount($xpath)
	{
		return $this->_number("getXpathCount", array($xpath));
	}
	/**
	 * 指定した要素に仮idを設定する
	 * Temporarily sets the "id" attribute of the specified element, so you can locate it in the future
	 * using its ID rather than a slow/complicated XPath.  This ID will disappear once the page is
	 * reloaded.
	 *
	 * @access public
	 * @param string $locator an element locator pointing to an element
	 * @param string $identifier a string to be used as the ID of the specified element
	 */
	function assignId($locator, $identifier)
	{
		$this->_command("assignId", array($locator, $identifier));
	}
	/**
	 * Seleniumがブラウザ仕様のXpathを使用できるようにする
	 * Specifies whether Selenium should use the native in-browser implementation
	 * of XPath (if any native version is available); if you pass "false" to
	 * this function, we will always use our pure-JavaScript xpath library.
	 * Using the pure-JS xpath library can improve the consistency of xpath
	 * element locators between different browser vendors, but the pure-JS
	 * version is much slower than the native implementations.
	 *
	 * @access public
	 * @param string $allow boolean, true means we'll prefer to use native XPath; false means we'll only use JS XPath
	 */
	function allowNativeXpath($allow)
	{
		$this->_command("allowNativeXpath", array($allow));
	}
	/**
	 * 値の無いXpathを無視する？
	 * Specifies whether Selenium will ignore xpath attributes that have no
	 * value, i.e. are the empty string, when using the non-native xpath
	 * evaluation engine. You'd want to do this for performance reasons in IE.
	 * However, this could break certain xpaths, for example an xpath that looks
	 * for an attribute whose value is NOT the empty string.
	 *
	 * The hope is that such xpaths are relatively rare, but the user should
	 * have the option of using them. Note that this only influences xpath
	 * evaluation when using the ajaxslt engine (i.e. not "javascript-xpath").
	 *
	 * @access public
	 * @param string $ignore boolean, true means we'll ignore attributes without value						at the expense of xpath "correctness"; false means						we'll sacrifice speed for correctness.
	 */
	function ignoreAttributesWithoutValue($ignore)
	{
		$this->_command("ignoreAttributesWithoutValue", array($ignore));
	}
	/**
	 * 指定したjavascriptを"true"が返るまで繰り返し実行する
	 * Runs the specified JavaScript snippet repeatedly until it evaluates to "true".
	 * The snippet may have multiple lines, but only the result of the last line
	 * will be considered.
	 *
	 * <p>
	 * Note that, by default, the snippet will be run in the runner's test window, not in the window
	 * of your application.  To get the window of your application, you can use
	 * the JavaScript snippet <code>selenium.browserbot.getCurrentWindow()</code>, and then
	 * run your JavaScript in there
	 * </p>
	 *
	 * @access public
	 * @param string $script the JavaScript snippet to run
	 * @param string $timeout a timeout in milliseconds, after which this command will return with an error
	 */
	function waitForCondition($script, $timeout=null)
	{
		if(is_null($timeout)) $timeout = $this->timeout;
		$this->_command("waitForCondition", array($script, $timeout));
	}
	/**
	 * タイムアウトを指定する
	 * Specifies the amount of time that Selenium will wait for actions to complete.
	 *
	 * <p>
	 * Actions that require waiting include "open" and the "waitFor*" actions.
	 * </p>
	 * The default timeout is 30 seconds.
	 *
	 * @access public
	 * @param string $timeout a timeout in milliseconds, after which the action will return with an error
	 */
	function setTimeout($timeout)
	{
//		$this->timeout = $timeout;
		$this->_command("setTimeout", array($timeout));
	}
	/**
	 * 新しいページを読み込むまで待つ
	 * Waits for a new page to load.
	 *
	 * <p>
	 * You can use this command instead of the "AndWait" suffixes, "clickAndWait", "selectAndWait", "typeAndWait" etc.
	 * (which are only available in the JS API).
	 * </p><p>
	 * Selenium constantly keeps track of new pages loading, and sets a "newPageLoaded"
	 * flag when it first notices a page load.  Running any other Selenium command after
	 * turns the flag to false.  Hence, if you want to wait for a page to load, you must
	 * wait immediately after a Selenium command that caused a page-load.
	 * </p>
	 *
	 * @access public
	 * @param string $timeout a timeout in milliseconds, after which this command will return with an error
	 */
	function waitForPageToLoad($timeout=null)
	{
		if(is_null($timeout)) $timeout = $this->timeout;
		$this->_command("waitForPageToLoad", array($timeout));
	}
	/**
	 * 新しいフレームが読み込まれるまで待つ
	 * Waits for a new frame to load.
	 *
	 * <p>
	 * Selenium constantly keeps track of new pages and frames loading,
	 * and sets a "newPageLoaded" flag when it first notices a page load.
	 * </p>
	 *
	 * See waitForPageToLoad for more information.
	 *
	 * @access public
	 * @param string $frameAddress FrameAddress from the server side
	 * @param string $timeout a timeout in milliseconds, after which this command will return with an error
	 */
	function waitForFrameToLoad($frameAddress, $timeout=null)
	{
		if(is_null($timeout)) $timeout = $this->timeout;
		$this->_command("waitForFrameToLoad", array($frameAddress, $timeout));
	}
	/**
	 * 現在のページの全てのクッキーを取得する
	 * Return all cookies of the current page under test.
	 *
	 * @access public
	 * @return string all cookies of the current page under test
	 */
	function getCookie()
	{
		return $this->_string("getCookie", array());
	}
	/**
	 * 指定した名前のクッキーを取得する
	 * Returns the value of the cookie with the specified name, or throws an error if the cookie is not present.
	 *
	 * @access public
	 * @param string $name the name of the cookie
	 * @return string the value of the cookie
	 */
	function getCookieByName($name)
	{
		return $this->_string("getCookieByName", array($name));
	}
	/**
	 * 指定した名前のクッキーが存在するか調べる
	 * Returns true if a cookie with the specified name is present, or false otherwise.
	 *
	 * @access public
	 * @param string $name the name of the cookie
	 * @return boolean true if a cookie with the specified name is present, or false otherwise.
	 */
	function isCookiePresent($name)
	{
		return $this->_bool("isCookiePresent", array($name));
	}
	/**
	 * クッキーを生成する
	 * Create a new cookie whose path and domain are same with those of current page
	 * under test, unless you specified a path for this cookie explicitly.
	 *
	 * @access public
	 * @param string $nameValuePair name and value of the cookie in a format "name=value"
	 * @param string $optionsString options for the cookie. Currently supported options include 'path', 'max_age' and 'domain'.	  the optionsString's format is "path=/path/, max_age=60, domain=.foo.com". The order of options are irrelevant, the unit	  of the value of 'max_age' is second.  Note that specifying a domain that isn't a subset of the current domain will	  usually fail.
	 */
	function createCookie($nameValuePair, $optionsString)
	{
		$this->_command("createCookie", array($nameValuePair, $optionsString));
	}
	/**
	 * 指定した名前のクッキーを削除する
	 * Delete a named cookie with specified path and domain.  Be careful; to delete a cookie, you
	 * need to delete it using the exact same path and domain that were used to create the cookie.
	 * If the path is wrong, or the domain is wrong, the cookie simply won't be deleted.  Also
	 * note that specifying a domain that isn't a subset of the current domain will usually fail.
	 *
	 * Since there's no way to discover at runtime the original path and domain of a given cookie,
	 * we've added an option called 'recurse' to try all sub-domains of the current domain with
	 * all paths that are a subset of the current path.  Beware; this option can be slow.  In
	 * big-O notation, it operates in O(n*m) time, where n is the number of dots in the domain
	 * name and m is the number of slashes in the path.
	 *
	 * @access public
	 * @param string $name the name of the cookie to be deleted
	 * @param string $optionsString options for the cookie. Currently supported options include 'path', 'domain'	  and 'recurse.' The optionsString's format is "path=/path/, domain=.foo.com, recurse=true".	  The order of options are irrelevant. Note that specifying a domain that isn't a subset of	  the current domain will usually fail.
	 */
	function deleteCookie($name, $optionsString)
	{
		$this->_command("deleteCookie", array($name, $optionsString));
	}
	/**
	 * 現在のページのクッキーを全て削除する
	 * Calls deleteCookie with recurse=true on all cookies visible to the current page.
	 * As noted on the documentation for deleteCookie, recurse=true can be much slower
	 * than simply deleting the cookies using a known domain/path.
	 *
	 * @access public
	 */
	function deleteAllVisibleCookies()
	{
		$this->_command("deleteAllVisibleCookies", array());
	}
	/**
	 * ブラウザのログレベルを指定する
	 * Sets the threshold for browser-side logging messages; log messages beneath this threshold will be discarded.
	 * Valid logLevel strings are: "debug", "info", "warn", "error" or "off".
	 * To see the browser logs, you need to
	 * either show the log window in GUI mode, or enable browser-side logging in Selenium RC.
	 *
	 * @access public
	 * @param string $logLevel one of the following: "debug", "info", "warn", "error" or "off"
	 */
	function setBrowserLogLevel($logLevel)
	{
		$this->_command("setBrowserLogLevel", array($logLevel));
	}
	/**
	 * 新しいscriptタグを生成する
	 * Creates a new "script" tag in the body of the current test window, and
	 * adds the specified text into the body of the command.  Scripts run in
	 * this way can often be debugged more easily than scripts executed using
	 * Selenium's "getEval" command.  Beware that JS exceptions thrown in these script
	 * tags aren't managed by Selenium, so you should probably wrap your script
	 * in try/catch blocks if there is any chance that the script will throw
	 * an exception.
	 *
	 * @access public
	 * @param string $script the JavaScript snippet to run
	 */
	function runScript($script)
	{
		$this->_command("runScript", array($script));
	}
	/**
	 * 新しいロケーション設定を追加する？
	 * Defines a new function for Selenium to locate elements on the page.
	 * For example,
	 * if you define the strategy "foo", and someone runs click("foo=blah"), we'll
	 * run your function, passing you the string "blah", and click on the element
	 * that your function
	 * returns, or throw an "Element not found" error if your function returns null.
	 *
	 * We'll pass three arguments to your function:
	 *
	 * <ul>
	 *
	 * <li>
	 * locator: the string the user passed in
	 * </li>
	 * <li>
	 * inWindow: the currently selected window
	 * </li>
	 * <li>
	 * inDocument: the currently selected document
	 * </li>
	 * </ul>
	 * The function must return null if the element can't be found.
	 *
	 * @access public
	 * @param string $strategyName the name of the strategy to define; this should use only   letters [a-zA-Z] with no spaces or other punctuation.
	 * @param string $functionDefinition a string defining the body of a function in JavaScript.   For example: <code>return inDocument.getElementById(locator);</code>
	 */
	function addLocationStrategy($strategyName, $functionDefinition)
	{
		$this->_command("addLocationStrategy", array($strategyName, $functionDefinition));
	}
	/**
	 * ページ全体のスクリーンショットを取る
	 * Saves the entire contents of the current window canvas to a PNG file.
	 * Currently this only works in Mozilla and when running in chrome mode.
	 * Contrast this with the captureScreenshot command, which captures the
	 * contents of the OS viewport (i.e. whatever is currently being displayed
	 * on the monitor), and is implemented in the RC only. Implementation
	 * mostly borrowed from the Screengrab! Firefox extension. Please see
	 * http://www.screengrab.org for details.
	 *
	 * @access public
	 * @param string $filename the path to the file to persist the screenshot as. No				  filename extension will be appended by default.				  Directories will not be created if they do not exist,					and an exception will be thrown, possibly by native				  code.
	 * @param string $kwargs a kwargs string that modifies the way the screenshot				  is captured. Example: "background=#CCFFDD" .				  Currently valid options:				  <dl>
<dt>background</dt>
<dd>the background CSS for the HTML document. This					 may be useful to set for capturing screenshots of					 less-than-ideal layouts, for example where absolute					 positioning causes the calculation of the canvas					 dimension to fail and a black background is exposed					 (possibly obscuring black text).</dd>
</dl>
	 */
	function captureEntirePageScreenshot($filename, $kwargs)
	{
		$this->_command("captureEntirePageScreenshot", array($filename, $kwargs));
	}
	/**
	 * rollupを行う
	 * Executes a command rollup, which is a series of commands with a unique
	 * name, and optionally arguments that control the generation of the set of
	 * commands. If any one of the rolled-up commands fails, the rollup is
	 * considered to have failed. Rollups may also contain nested rollups.
	 *
	 * @access public
	 * @param string $rollupName the name of the rollup command
	 * @param string $kwargs keyword arguments string that influences how the					rollup expands into commands
	 */
	function rollup($rollupName, $kwargs)
	{
		$this->_command("rollup", array($rollupName, $kwargs));
	}
	/**
	 * ステータスバーにメッセージを書き込む
	 * Writes a message to the status bar and adds a note to the browser-side
	 * log.
	 *
	 * @access public
	 * @param string $context the message to be sent to the browser
	 */
	function setContext($context)
	{
		$this->_command("setContext", array($context));
	}
	/**
	 * ファイルを添付する
	 * Sets a file input (upload) field to the file listed in fileLocator
	 *
	 * @access public
	 * @param string $fieldLocator an element locator
	 * @param string $fileLocator a URL pointing to the specified file. Before the file  can be set in the input field (fieldLocator), Selenium RC may need to transfer the file	to the local machine before attaching the file in a web page form. This is common in selenium  grid configurations where the RC server driving the browser is not the same  machine that started the test.   Supported Browsers: Firefox ("*chrome") only.
	 */
	function attachFile($fieldLocator, $fileLocator)
	{
		$this->_command("attachFile", array($fieldLocator, $fileLocator));
	}
	/**
	 * デスクトップ全体のスクリーンショットを取る
	 * Captures a PNG screenshot to the specified file.
	 *
	 * @access public
	 * @param string $filename the absolute path to the file to be written, e.g. "c:\blah\screenshot.png"
	 */
	function captureScreenshot($filename)
	{
		$this->_command("captureScreenshot", array($filename));
	}
	/**
	 * デスクトップ全体のスクリーンショットを撮ってbase64エンコード文字列として取得する
	 * Capture a PNG screenshot.  It then returns the file as a base 64 encoded string.
	 *
	 * @access public
	 * @return string The base 64 encoded string of the screen shot (PNG file)
	 */
	function captureScreenshotToString()
	{
		return $this->_string("captureScreenshotToString", array());
	}
	/**
	 * Seleniumサーバを終了する
	 * Kills the running Selenium Server and all browser sessions.  After you run this command, you will no longer be able to send
	 * commands to the server; you can't remotely start the server once it has been stopped.  Normally
	 * you should prefer to run the "stop" command, which terminates the current browser session, rather than
	 * shutting down the entire server.
	 *
	 * @access public
	 */
	function shutDownSeleniumServer()
	{
		$this->_command("shutDownSeleniumServer", array());
	}
	/**
	 *
	 * Simulates a user pressing a key (without releasing it yet) by sending a native operating system keystroke.
	 * This function uses the java.awt.Robot class to send a keystroke; this more accurately simulates typing
	 * a key on the keyboard.  It does not honor settings from the shiftKeyDown, controlKeyDown, altKeyDown and
	 * metaKeyDown commands, and does not target any particular HTML element.  To send a keystroke to a particular
	 * element, focus on the element first before running this command.
	 *
	 * @access public
	 * @param string $keycode an integer keycode number corresponding to a java.awt.event.KeyEvent; note that Java keycodes are NOT the same thing as JavaScript keycodes!
	 */
	function keyDownNative($keycode)
	{
		$this->_command("keyDownNative", array($keycode));
	}
	  /**
	 * Simulates a user releasing a key by sending a native operating system keystroke.
	 * This function uses the java.awt.Robot class to send a keystroke; this more accurately simulates typing
	 * a key on the keyboard.  It does not honor settings from the shiftKeyDown, controlKeyDown, altKeyDown and
	 * metaKeyDown commands, and does not target any particular HTML element.  To send a keystroke to a particular
	 * element, focus on the element first before running this command.
	 *
	 * @access public
	 * @param string $keycode an integer keycode number corresponding to a java.awt.event.KeyEvent; note that Java keycodes are NOT the same thing as JavaScript keycodes!
	 */
	function keyUpNative($keycode)
	{
		$this->_command("keyUpNative", array($keycode));
	}
	  /**
	 * Simulates a user pressing and releasing a key by sending a native operating system keystroke.
	 * This function uses the java.awt.Robot class to send a keystroke; this more accurately simulates typing
	 * a key on the keyboard.  It does not honor settings from the shiftKeyDown, controlKeyDown, altKeyDown and
	 * metaKeyDown commands, and does not target any particular HTML element.  To send a keystroke to a particular
	 * element, focus on the element first before running this command.
	 *
	 * @access public
	 * @param string $keycode an integer keycode number corresponding to a java.awt.event.KeyEvent; note that Java keycodes are NOT the same thing as JavaScript keycodes!
	 */
	function keyPressNative($keycode)
	{
		$this->_command("keyPressNative", array($keycode));
	}

	function clickAndWait($locator){
		$this->click($locator);
		$this->waitForPageToLoad();
	}

	function captureFullScreenshot($filename){
		$this->windowMaximize();
		$this->captureScreenshot($filename);
	}

	function captureFullScreenshotToString(){
		$this->windowMaximize();
		return $this->captureScreenshotToString();
	}

	function _string($verb, $args = array())
	{
		$result = $this->_command($verb, $args);
		return (strlen($result) > 3) ? substr($result, 3) : '';
	}

	function _command($verb, $args = array())
	{
		$variables = array("cmd"=> $verb,"sessionId"=>$this->sessionId);
		for($i=0;$i<count($args);$i++){
			$variables[$i+1] = $args[$i];
		}
		if($variables["cmd"]==="getNewBrowserSession"){
			return $this->browser->get($this->hostUrl.'?'.urldecode(TemplateFormatter::httpBuildQuery($variables)));
		}else{
			return $this->browser->get($this->hostUrl.'?'.TemplateFormatter::httpBuildQuery($variables));
		}
	}
	function _number($verb, $args = array())
	{
		$result = $this->_string($verb, $args);
		if (!is_numeric($result)) {
			ExceptionTrigger::raise(new GenericException('result is not numeric.'));
		}
		return $result;
	}
	function _stringArray($verb, $args = array())
	{
		$csv = $this->_string($verb, $args);
		$token = '';
		$tokens = array();
		$letters = preg_split('//', $csv, -1, PREG_SPLIT_NO_EMPTY);
		for ($i = 0; $i < count($letters); $i++) {
			$letter = $letters[$i];
			switch($letter) {
			case '\\':
				$i++;
				$letter = $letters[$i];
				$token = $token . $letter;
				break;
			case ',':
				array_push($tokens, $token);
				$token = '';
				break;
			default:
				$token = $token . $letter;
				break;
			}
		}
		array_push($tokens, $token);
		return $tokens;
	}
	function _bool($verb, $args = array())
	{
		$result = $this->_string($verb, $args);
		if ($result==="true") {
			return true;
		}elseif($result==="false"){
			return false;
		}else{
			ExceptionTrigger::raise(new GenericException('true false以外の結果になった',array($result)));
		}
	}

}
?>