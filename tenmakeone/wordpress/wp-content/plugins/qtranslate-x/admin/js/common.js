/*
	Copyright 2014  qTranslate Team  (email : qTranslateTeam@gmail.com )

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/
/**
 * Search for 'Designed as interface for other plugin integration' in comments to functions
 * to find out which functions are safe to use in the 3rd-party integration.
 * Avoid accessing internal variables directly, as they are subject to be re-designed at any time.
*/
/*
// debugging tools, do not check in
var cc=0;
function c(v){ ++cc; console.log('== '+cc+': '+v); }
function ct(v){ c(v); console.trace(); }
function co(t,o){ ++cc; console.log('== '+cc+': '+t+'%o',o); }
*/

/**
 * since 3.2.7
 */
qtranxj_get_split_blocks = function(text)
{
	//var split_regex = /(<!--:[a-z]{2}-->|<!--:-->|\[:[a-z]{2}\]|\[:\]|\{:[a-z]{2}\}|\{:\})/gi;
	var split_regex = /(<!--:[a-z]{2}-->|<!--:-->|\[:[a-z]{2}\]|\[:\])/gi;
	return text.xsplit(split_regex);
}

/**
 * since 3.2.7
 */
qtranxj_split = function(text)
{
	var blocks = qtranxj_get_split_blocks(text);
	return qtranxj_split_blocks(blocks);
}

/**
 * since 3.1-b1 - closing tag [:]
 */
qtranxj_split_blocks = function(blocks)
{
	var result = new Object;
	//for(var i=0; i<qTranslateConfig.enabled_languages.length; ++i)
	for(var lang in qTranslateConfig.language_config)
	{
		//var lang=qTranslateConfig.enabled_languages[i];
		result[lang] = '';
	}
	//if(!qtranxj_isArray(blocks))//since 3.2.7
	if(!blocks || !blocks.length)
		return result;
	if(blocks.length==1){//no language separator found, enter it to all languages
		var b=blocks[0];
		//for(var j=0; j<qTranslateConfig.enabled_languages.length; ++j){
		for(var lang in qTranslateConfig.language_config){
			//var lang=qTranslateConfig.enabled_languages[j];
			result[lang] += b;
		}
		return result;
	}
	var clang_regex=/<!--:([a-z]{2})-->/gi;
	var blang_regex=/\[:([a-z]{2})\]/gi;
	//var slang_regex=/\{:([a-z]{2})\}/gi; //maybe later we will need it?
	var lang = false;
	var matches;
	for(var i = 0;i<blocks.length;++i){
		var b=blocks[i];
		//c('blocks['+i+']='+b);
		if(!b.length) continue;
		matches = clang_regex.exec(b); clang_regex.lastIndex=0;
		if(matches!=null){
			lang = matches[1];
			continue;
		}
		matches = blang_regex.exec(b); blang_regex.lastIndex=0;
		if(matches!=null){
			lang = matches[1];
			continue;
		}
		//matches = slang_regex.exec(b); slang_regex.lastIndex=0;
		//if(matches!=null){
		//	lang = matches[1];
		//	continue;
		//}
		if( b == '<!--:-->' || b == '[:]' ){// || b == '{:}' ){
			lang = false;
			continue;
		}
		if(lang){
			result[lang] += b;
			lang = false;
		}else{//keep neutral text
			for(var key in result){
				result[key] += b;
			}
		}
	}
	return result;
}

function qtranxj_get_cookie(cname)
{
	var nm = cname + "=";
	var ca = document.cookie.split(';');
	//c('ca='+ca);
	for(var i=0; i<ca.length; ++i){
		var s = ca[i];
		var sa = s.split('=');
		if(sa[0].trim()!=cname) continue;
		if(ca.length<2) continue;
		return sa[1].trim();
	}
	return '';
}

String.prototype.xsplit = function(_regEx){
	// Most browsers can do this properly, so let them work, they'll do it faster
	if ('a~b'.split(/(~)/).length === 3){ return this.split(_regEx); }

	if (!_regEx.global)
	{ _regEx = new RegExp(_regEx.source, 'g' + (_regEx.ignoreCase ? 'i' : '')); }

	// IE (and any other browser that can't capture the delimiter)
	// will, unfortunately, have to be slowed down
	var start = 0, arr=[];
	var result;
	while((result = _regEx.exec(this)) != null){
		arr.push(this.slice(start, result.index));
		if(result.length > 1) arr.push(result[1]);
		start = _regEx.lastIndex;
	}
	if(start < this.length) arr.push(this.slice(start));
	if(start == this.length) arr.push(''); //delim at the end
	return arr;
};

//Since 3.2.7 removed: function qtranxj_isArray(obj){ return obj.constructor.toString().indexOf('Array') >= 0; }

function qtranxj_ce(tagName, props, pNode, isFirst)
{
	var el= document.createElement(tagName);
	if (props)
	{
		for(prop in props)
		{
			//try
			{
				el[prop]=props[prop];
			}
			//catch(err)
			{
				//Handle errors here
			}
		}
	}
	if (pNode)
	{
		if (isFirst && pNode.firstChild)
		{
			pNode.insertBefore(el, pNode.firstChild);
		}
		else
		{
			pNode.appendChild(el);
		}
	}
	return el;
}

var qTranslateX=function(pg)
{
	this.ge=function(id){ return document.getElementById(id); }

	/**
	 * Designed as interface for other plugin integration. The documentation is available at
	 * https://qtranslatexteam.wordpress.com/integration/
	 * return array keyed by two-letter language code. Example of usage:
	 * var langs = getLanguages();
	 * for(var lang_code in langs){
	 *  var lang_conf = langs[lang_code];
	 *  // variables available:
	 *  //lang_conf.name
	 *  //lang_conf.flag
	 *  //lang_conf.locale
	 *  // and may be more properties later
	 * }
	 */
	this.getLanguages=function(){ return qTranslateConfig.language_config; }

	/**
	 * Designed as interface for other plugin integration. The documentation is available at
	 * https://qtranslatexteam.wordpress.com/integration/
	 * return URL to folder with flag images.
	 */
	this.getFlagLocation=function(){ return qTranslateConfig.flag_location; }

	/**
	 * Designed as interface for other plugin integration. The documentation is available at
	 * https://qtranslatexteam.wordpress.com/integration/
	 * return true if 'lang' is in the hash of enabled languages.
	 * This function maybe needed, as function qtranxj_split may return languages,
	 * which are not enabled, in case they were previously enabled and had some data.
	 * Such data is preserved and re-saved until user deletes it manually.
	 */
	this.isLanguageEnabled=function(lang){ return !!qTranslateConfig.language_config[lang]; }
	//this.isLanguageEnabled=function(lang)
	//{
	//	for(var i=0; i<qTranslateConfig.enabled_languages.length; ++i){
	//		if(qTranslateConfig.enabled_languages[i]==lang) return true;
	//	}
	//	return false;
	//}

	setLangCookie=function(lang) { document.cookie='qtrans_edit_language='+lang; }

	qTranslateConfig.activeLanguage;
	if(qTranslateConfig.LSB){
		qTranslateConfig.activeLanguage = qtranxj_get_cookie('qtrans_edit_language');
		if(!qTranslateConfig.activeLanguage || !this.isLanguageEnabled(qTranslateConfig.activeLanguage)){
			qTranslateConfig.activeLanguage = qTranslateConfig.language;
			if(this.isLanguageEnabled(qTranslateConfig.activeLanguage)){
				setLangCookie(qTranslateConfig.activeLanguage);
			}else{//no languages are enabled
				qTranslateConfig.LSB = false;
			}
		}
	}else{
		qTranslateConfig.activeLanguage = qTranslateConfig.language;
		setLangCookie(qTranslateConfig.activeLanguage);
	}

	this.getActiveLanguage = function() { return qTranslateConfig.activeLanguage; }
	//this.getActiveLanguageName = function() { return qTranslateConfig.language_name[qTranslateConfig.activeLanguage]; }
	this.getLanguages = function() { return qTranslateConfig.language_config; }

	var contentHooks={};
	var contentHookId = 0;

/* since 3.2.9.8 - h.contents -> h.fields
	updateFusedValueHooked=function(h)
	{
		switch(h.separator){
			case '<': h.mlContentField.value = qtranxj_join_c(h.contents); break;
			case 'byline': h.mlContentField.value = qtranxj_join_byline(h.contents); break;
			case '[':
			default: h.mlContentField.value = qtranxj_join_b(h.contents); break;
		}
		//c('updateFusedValueHooked['+h.mce.id+'] text:'+h.mlContentField.value);
	}
*/

	updateFusedValueH=function(id,value)
	{
		var h=contentHooks[id];
		var text=value.trim();
		//c('updateFusedValueH['+id+'] lang='+h.lang+'; text:'+text);
		//h.contents[h.lang]=text;
		h.fields[h.lang].value=text;
		//updateFusedValueHooked(h);
	}

	addContentHook=function(inpField,form,separator)
	{
		//co('addContentHook: inpField:',inpField);
		if( !inpField ) return false;
		if( !inpField.name ) return false;
		//if( typeof inpField.value !== 'string' ) return false;
		switch(inpField.tagName){
			case 'TEXTAREA':
			case 'INPUT': break;
			default: return false;
		}
		if(!inpField.id){
			inpField.id = inpField.tagName;
			if(form.id) inpField.id += form.id;
			inpField.id += inpField.name;
			if( inpField.name > 2 && inpField.name.lastIndexOf('[]') == inpField.name.length-2 ){
				inpField.id += (++contentHookId);// then second call to addContentHook for the same field will create additional set - no good, but should not happen
			}
		}
		if(contentHooks[inpField.id]) return true;
		var h=contentHooks[inpField.id]={};
		//h.id=inpField.id;
		h.name=inpField.name;
		h.contentField=inpField;
		//c('addContentHook: inpField.value='+inpField.value);
		h.lang=qTranslateConfig.activeLanguage;
		var contents=qtranxj_split(inpField.value);//keep neutral text from older times, just in case.
		                        //inpField.tagName
		inpField.value = contents[h.lang];
		var bfnm, sfnm, p = h.name.indexOf('[');
		if(p<0){
			bfnm = 'qtranslate-fields['+h.name+']';
		}else{
			bfnm = 'qtranslate-fields['+h.name.substring(0,p)+']';
			if(h.name.lastIndexOf('[]') < 0){
				bfnm += h.name.substring(p);
			}else{
				var len = h.name.length-2;
				if(len > p) bfnm += h.name.substring(p,len);
				sfnm = '[]';
			}
		}
		h.fields={};
		for(var lang in contents){
			var text = contents[lang];
			var fnm = bfnm+'['+lang+']';
			if(sfnm) fnm += sfnm;
			var f = qtranxj_ce('input', {name: fnm, type: 'hidden', className: 'hidden', value: text});
			h.fields[lang] = f;
			inpField.parentNode.insertBefore(f,inpField);
		}
		/* since 3.2.9.8 - h.contents -> h.fields
		//h.mlContentField=qtranxj_ce('input', {name: inpField.name, type: 'hidden', className: 'hidden', value: inpField.value}, form);
		h.mlContentField=qtranxj_ce('input', {name: inpField.name, type: 'hidden', className: 'hidden', value: inpField.value});
		inpField.name='edit-'+inpField.name;
		inpField.parentNode.insertBefore(h.mlContentField,inpField);
		inpField.onblur=function(){ updateFusedValueH(this.id,this.value); }
		var text = h.contents[h.lang];
		inpField.value=text;
		*/
		if(!separator){
			//if(inpField.tagName==='TEXTAREA')
			//	separator='<';
			//else
				separator='[';//since 3.1 we get rid of <--:--> encoding
		}
		// since 3.2.9.8 - h.contents -> h.fields
		h.sepfield = qtranxj_ce('input', {name: bfnm+'[qtranslate-separator]', type: 'hidden', className: 'hidden', value: separator });
		inpField.parentNode.insertBefore(h.sepfield,inpField);
		h.separator=separator;

		/**
		 * Highlighting the translatable fields
		 * @since 3.2-b3
		*/
		inpField.className += ' qtranxs-translatable';

		/*
		if(window.tinyMCE){
			//c('addContentHook: window.tinyMCE: tinyMCE.editors.length='+tinyMCE.editors.length);
			//tinyMCE.editors are not yet set up at this point.
			for(var i=0; i<tinyMCE.editors.length; ++i){
				var ed=tinyMCE.editors[i];
				if(ed.id != inpField.id) continue;
				//c('addContentHook:updateTinyMCE: ed.id='+ed.id);//never fired yet
				h.mce=ed;
				//updateTinyMCE(ed,text);
				updateTinyMCE(h);
			}
		}
		*/
		return h;
	}
	this.addContentHookC=function(inpField,form) { return addContentHook(inpField,form,'['); }//'<'
	this.addContentHookB=function(inpField,form) { return addContentHook(inpField,form,'['); }

	this.addContentHookById=function(id,form,sep) { return addContentHook(this.ge(id),form,sep); }
	this.addContentHookByIdName=function(nm,form)
	{
		var sep;
		//if(nm.indexOf('<')==0 || nm.indexOf('[')==0){
		switch(nm[0]){
			case '<':
			case '[':
				sep=nm.substring(0,1);
				nm=nm.substring(1);
				break;
			default: break;
		}
		return this.addContentHookById(nm,form,sep);
	}
	this.addContentHookByIdC=function(id,form) { return this.addContentHookById(id,form,'['); }//'<'
	this.addContentHookByIdB=function(id,form) { return this.addContentHookById(id,form,'['); }

	this.removeContentHook=function(inpField)
	{
		if( !inpField ) return false;
		if( !inpField.id ) return false;
		if( !contentHooks[inpField.id] ) return false;
		var h=contentHooks[inpField.id];
		/* @since 3.2.9.8 - h.contents -> h.fields
		inpField.onblur = function(){};
		inpField.name=inpField.name.replace(/^edit-/,'');
		inpField.value=h.mlContentField.value;
		jQuery(h.mlContentField).remove();
		*/
		if(h.sepfield) jQuery(h.sepfield).remove();
		for(var lang in h.fields){
			jQuery(h.fields[lang]).remove();
		}
		jQuery(inpField).removeClass('qtranxs-translatable');
		delete contentHooks[inpField.id];
		return true;
	};

	/**
	 * @since 3.2.7
	 */
	var displayHookNodes=[];
	addDisplayHookNode=function(nd)
	{
		if(!nd.nodeValue) return 0;
		var blocks = qtranxj_get_split_blocks(nd.nodeValue);
		if( !blocks || !blocks.length || blocks.length == 1 ) return 0;
		var h={};
		h.nd=nd;
		//co('addDisplayHookNode: nd=',nd);
		//c('addDisplayHookNode: nodeValue: "'+nd.nodeValue+'"');
		//c('addDisplayHookNode: content='+content);
		h.contents = qtranxj_split_blocks(blocks);
		nd.nodeValue=h.contents[qTranslateConfig.activeLanguage];
		displayHookNodes.push(h);
		return 1;
	}

	/**
	 * @since 3.2.7
	 */
	var displayHookAttrs=[];
	addDisplayHookAttr=function(nd)
	{
		if(!nd.value) return 0;
		var blocks = qtranxj_get_split_blocks(nd.value);
		if( !blocks || !blocks.length || blocks.length == 1 ) return 0;
		var h={};
		h.nd=nd;
		h.contents = qtranxj_split_blocks(blocks);
		nd.value=h.contents[qTranslateConfig.activeLanguage];
		displayHookAttrs.push(h);
		return 1;
	}

	/**
	 * @since 3.2.7 switched to use of nodeValue instead of innerHTML.
	 */
	addDisplayHook=function(elem)
	{
		if(!elem || !elem.tagName) return 0;
		switch(elem.tagName){
			case 'TEXTAREA': return 0;
			case 'INPUT':
				switch(elem.type){
					case 'submit': if(elem.value) return addDisplayHookAttr(elem);
					default: return 0;
				}
			default: break;
		}
		var cnt = 0;
		if(elem.childNodes && elem.childNodes.length){
			for(var i = 0; i < elem.childNodes.length; ++i){
				var nd = elem.childNodes[i];
				switch(nd.nodeType){//http://www.w3.org/TR/REC-DOM-Level-1/level-one-core.html#ID-1950641247
					case 1://ELEMENT_NODE
						cnt += addDisplayHook(nd);//recursive call
						break;
					case 2://ATTRIBUTE_NODE
					case 3://TEXT_NODE
						cnt += addDisplayHookNode(nd);
						break;
					default: break;
				}
			}
		}
		return cnt;
	}

	this.addDisplayHookById=function(id) { return addDisplayHook(this.ge(id)); }

	updateTinyMCE=function(h)
	{
		text = h.contentField.value;
		//co('updateTinyMCE: window.switchEditors: ',window.switchEditors);
		//c('updateTinyMCE: text:'+text);
		if(h.wpautop && window.switchEditors){
			//text = window.switchEditors.pre_wpautop( text );
			text = window.switchEditors.wpautop(text);
			//c('updateTinyMCE:wpautop:'+text);
		}
		h.mce.setContent(text,{format: 'html'});
	}

	onTabSwitch=function(lang)
	{
		//var qtx = this;
		setLangCookie(lang);
		for(var i=0; i<displayHookNodes.length; ++i){
			var h=displayHookNodes[i];
			h.nd.nodeValue = h.contents[lang];
		}
		for(var i=0; i<displayHookAttrs.length; ++i){
			var h=displayHookAttrs[i];
			h.nd.value = h.contents[lang];
		}
		for(var key in contentHooks){
			var h=contentHooks[key];
			var mce = h.mce && !h.mce.hidden;
			if(mce){
				h.mce.save({format: 'html'});
			}
			h.fields[h.lang].value = h.contentField.value;
			h.lang = lang;
			var value = h.fields[h.lang].value;
			if(h.contentField.placeholder && value != ''){//since 3.2.7
				h.contentField.placeholder='';
			}
			h.contentField.value = value;
			if(mce){
				updateTinyMCE(h);
			}
		}
	}

	qTranslateConfig.qtx = this;
/*
	onTabSwitchCustom=function()
	{
		//co('onTabSwitch: this',this);
		//co('onTabSwitch: qtx',qTranslateConfig.qtx);
		pg.onTabSwitch(this.lang,qTranslateConfig.qtx);
	}
*/

	addDisplayHooks=function(elems)
	{
		//c('addDisplayHooks: elems.length='+elems.length);
		for(var i=0; i<elems.length; ++i){
			var e=elems[i];
			//co('addDisplayHooks: e=',e);
			//co('addDisplayHooks: e.tagName=',e.tagName);
			addDisplayHook(e);
		}
	}

	this.addDisplayHooksByClass=function(nm,container)
	{
		//co('addDisplayHooksByClass: container:',container);
		var elems=container.getElementsByClassName(nm);
		//co('addDisplayHooksByClass: elems('+nm+'):',elems);
		//co('addDisplayHooksByClass: elems.length=',elems.length);
		addDisplayHooks(elems);
	}

	this.addDisplayHooksByTagInClass=function(nm,tag,container)
	{
		var elems=container.getElementsByClassName(nm);
		//c('addDisplayHooksByClass: elems.length='+elems.length);
		for(var i=0; i<elems.length; ++i){
			var elem=elems[i];
			var items=elem.getElementsByTagName(tag);
			addDisplayHooks(items);
		}
	}

	/**
	 * @since 3.1-b2
	*/
	addContentFieldHooks=function(fields,form,sep)
	{
		for(var i=0; i<fields.length; ++i){
			var f=fields[i];
			//if(sep=='[') //co('addContentHooksByClass: f: ',f);
			addContentHook(f,form,sep);
		}
	}

	addContentHooksByClassName=function(nm,form,container,sep)
	{
		if(!container) container=form;
		var fields=container.getElementsByClassName(nm);
		//if(sep=='[') //c('addContentHooksByClass: fields.length='+fields.length);
		addContentFieldHooks(fields,form,sep);
	}

	this.addContentHooksByClass=function(nm,form,container)
	{
		var sep;
		if(nm.indexOf('<')==0 || nm.indexOf('[')==0){
			sep=nm.substring(0,1);
			nm=nm.substring(1);
		}
		addContentHooksByClassName(nm,form,container,sep);
	}

	/**
	 * adds custom hooks from configuration
	 * @since 3.1-b2 - renamed to addCustomContentHooks, since addContentHooks used in qTranslateConfig.js
	 * @since 3.0 - addContentHooks
	*/
	this.addCustomContentHooks=function(form)
	{
		//c('qTranslateConfig.custom_fields.length='+qTranslateConfig.custom_fields.length);
		for(var i=0; i<qTranslateConfig.custom_fields.length; ++i){
			var nm=qTranslateConfig.custom_fields[i];
			this.addContentHookByIdName(nm,form);
		}
		for(var i=0; i<qTranslateConfig.custom_field_classes.length; ++i){
			var nm=qTranslateConfig.custom_field_classes[i];
			this.addContentHooksByClass(nm,form);
		}
	}

	/**
	 * Parses custom page configuration, loaded in qtranxf_load_admin_page_config.
	 * @since 3.1-b2
	*/
	this.addPageHooks=function(page_config_forms)
	{
		for(var p=0; p < page_config_forms.length; ++p){
			var frm = page_config_forms[p];
			var form;
			if(frm.form){
				if(frm.form.id){
					form = document.getElementById(frm.form.id);
				}else if(frm.form.jquery){
					form = $(frm.form.jquery);
				}else if(frm.form.name){
					var elms = document.getElementsByName(frm.form.name);
					if(elms && elms.length){
						form = elms[0];
					//}else{
					//	alert('qTranslate-X misconfiguraton: form with name "'+frm.form.name+'" is not found.');
					}
				}
			}else{
				form = this.getWrapForm();
			}
			//co('form=',form);
			//c('frm.fields.length='+frm.fields.length);
			for(var f=0; f < frm.fields.length; ++f){
				var fld = frm.fields[f];
				//co('fld=',fld);
				//c('encode='+fld.encode);
				//c('id='+fld.id);
				//c('class='+fld.class);
				var containers=[];
				if(fld.container_id){
					var container = document.getElementById(fld.container_id);
					if(container) containers.push(container);
				}else if(fld.container_class){
					containers = document.getElementsByClassName(fld.container_class);
				}else if(form){
					containers.push(form);
				}
				var sep = fld.encode;
				switch( sep ){
					case 'display':
						if(fld.id) addDisplayHook(document.getElementById(fld.id));
						else if(fld.class){
							//c('addPageHooks: display: class='+fld.class+'; fld.tag='+fld.tag);
							//c('class='+fld.class+'; containers.length='+containers.length);
							for(var i=0; i < containers.length; ++i){
								var container = containers[i];
								var fields=container.getElementsByClassName(fld.class);
								for(var j=0; j<fields.length; ++j){
									var field=fields[j];
									//c('field.tagName='+field.tagName);
									if(fld.tag && fld.tag != field.tagName) continue;
									addDisplayHook(field);
								}
								//this.addDisplayHooksByClass(fld.class,container);
							}
						}else if(fld.tag){
							//c('tag='+fld.tag+'; containers.length='+containers.length);
							for(var i=0; i < containers.length; ++i){
								var container = containers[i];
								//co('container=',container);
								var elems=container.getElementsByTagName(fld.tag);
								//co('elems=',elems);
								addDisplayHooks(elems);
							}
						}else{
							continue;
						}
						break;
					case '[':
					case '<':
					case 'byline':
					default:
						if(!form) continue;
						if(fld.id) this.addContentHookById(fld.id,form,sep);
						else if(fld.class){
							for(var i=0; i < containers.length; ++i){
								var container = containers[i];
								var fields=container.getElementsByClassName(fld.class);
								for(var j=0; j<fields.length; ++j){
									var field=fields[j];
									if(fld.tag && fld.tag != field.tagName) continue;
									if(fld.name && (!field.name || fld.name != field.name)) continue;
									addContentHook(field,form,sep);
								}
								//addContentHooksByClassName(fld.class,form,container,sep);
							}
						}else if(fld.tag){
							for(var i=0; i < containers.length; ++i){
								var container = containers[i];
								var fields=container.getElementsByTagName(fld.tag);
								for(var j=0; j<fields.length; ++j){
									var field=fields[j];
									if(fld.name && (!field.name || fld.name != field.name)) continue;
									addContentHook(field,form,sep);
								}
							}
						}else{
							continue;
						}
						break;
				}
			}
		}
	}

	this.addContentHooksTinyMCE=function()
	{
		function setEditorHooks(ed)
		{
			var id = ed.id;
			if (!id) return;
			var h=contentHooks[id];
			if(!h) return;
			if(h.mce){
				//already initialized
				return;
			}
			h.mce=ed;

			/**
			 * Highlighting the translatable fields
			 * @since 3.2-b3
			*/
			ed.getContainer().className += ' qtranxs-translatable';
			ed.getElement().className += ' qtranxs-translatable';

			var updateTinyMCEonInit = h.updateTinyMCEonInit;
			if(updateTinyMCEonInit == null){// 'tmce-active' or 'html-active' was not provided on the wrapper.
				var text_e = ed.getContent({format: 'html'}).replace(/\s+/g,'');
				var text_h = h.contentField.value.replace(/\s+/g,'');
				/**
				 * @since 3.2.9.8 - this is an ugly trick.
				 * Before this version, it was working relying on properly timed synchronisation of the page loading process,
				 * which did not work correctly in some browsers like IE or MAC OS, for example.
				 * Now, function setTinyMceInit is called after HTML loaded, before TinyMCE initialization, and it always set
				 * tinyMCEPreInit.mceInit, which causes to call this function, setEditorHooks, on TinyMCE initialization of each editor.
				 * However, function setEditorHooks gets invoked in two ways:
				 *
				 * 1. On page load, when Visual mode is initially on.
				 *      In this case we need to apply updateTinyMCE, which possibly applies wpautop.
				 *      Without q-X, WP applies wpautop in this case in php code in /wp-includes/class-wp-editor.php,
				 *      function 'editor', line "add_filter('the_editor_content', 'wp_richedit_pre');".
				 *      q-X disables this call in 'function qtranxf_the_editor',
				 *      since wpautop does not work correctly on multilingual values, and there is no filter to adjust its behaviour.
				 *      So, here we have to apply back wpautop to single-language value, which is achieved
				 *      with a call to updateTinyMCE(h) below.
				 *
				 * 2. When user switches to Visual mode for the first time from a page, which was initially loaded in Text mode.
				 *      In this case, wpautop gets applied internally inside TinyMCE, and we do not need to call updateTinyMCE(h) below.
				 *
				 * We could not figure out a good way to distinct within this function which way it was called,
				 * except this tricky comparison on the next line.
				 *
				 * If somebody finds out a better way, please let us know at qtranslateteam@gmail.com.
				*/
				updateTinyMCEonInit = text_e != text_h;
			}
			if(updateTinyMCEonInit){
				updateTinyMCE(h);
			}
			return h;
		}

		/** Sets hooks on HTML-loaded TinyMCE editors via tinyMCEPreInit.mceInit. */
		setTinyMceInit=function()
		{
			if (!window.tinyMCE) return;
			for(var key in contentHooks){
				var h=contentHooks[key];
				if(h.contentField.tagName!=='TEXTAREA') continue;
				if(h.mce) continue;
				if(h.mceInit) continue;
				if(!tinyMCEPreInit.mceInit[key]) continue;
				h.mceInit=tinyMCEPreInit.mceInit[key];
				if(h.mceInit.wpautop){
					h.wpautop = h.mceInit.wpautop;
					var wrappers = tinymce.DOM.select( '#wp-' + key + '-wrap' );
					if(wrappers && wrappers.length){
						h.wrapper = wrappers[0];
						if(h.wrapper){
							if(tinymce.DOM.hasClass( h.wrapper, 'tmce-active')) h.updateTinyMCEonInit = true;
							if(tinymce.DOM.hasClass( h.wrapper, 'html-active')) h.updateTinyMCEonInit = false;
							//otherwise h.updateTinyMCEonInit stays undetermined
						}
					}
				}else{
					h.updateTinyMCEonInit = false;
				}
				tinyMCEPreInit.mceInit[key].init_instance_callback = function(ed){ setEditorHooks(ed); }
			}
		}
		setTinyMceInit();

		/** Adds more TinyMCE editors, which may have been initialized dynamically. */
		loadTinyMceHooks=function()
		{
			if (!window.tinyMCE) return;
			for(var i=0; i<tinyMCE.editors.length; ++i){
				var ed=tinyMCE.editors[i];
				setEditorHooks(ed);
			}
		}
		window.addEventListener('load', loadTinyMceHooks);
	}

	if(!qTranslateConfig.onTabSwitchFunctions) qTranslateConfig.onTabSwitchFunctions=[];
	if(!qTranslateConfig.onTabSwitchFunctionsSave) qTranslateConfig.onTabSwitchFunctionsSave=[];
	if(!qTranslateConfig.onTabSwitchFunctionsLoad) qTranslateConfig.onTabSwitchFunctionsLoad=[];

	this.addLanguageSwitchListener=function(func){ qTranslateConfig.onTabSwitchFunctions.push(func); }

	/**
	 * @since 3.2.9.8.6
	 * Designed as interface for other plugin integration. The documentation is available at
	 * https://qtranslatexteam.wordpress.com/integration/
	 * The function passed will be called when user presses one of the Language Switching Buttons
	 * before the content of all fields hooked is replaced with an appropriate language.
	 * Two arguments are supplied:
	 * - two-letter language code of currently active language from which the edit language is being switched.
	 * - the language code to which the edit language is being switched.
	 * The value of "this" is set to the only global instance of qTranslateX object.
	 */
	this.addLanguageSwitchBeforeListener=function(func){ qTranslateConfig.onTabSwitchFunctionsSave.push(func); }

	/**
	 * @since 3.2.9.8.6
	 * Designed as interface for other plugin integration. The documentation is available at
	 * https://qtranslatexteam.wordpress.com/integration/
	 * The function passed will be called when user presses one of the Language Switching Buttons
	 * after the content of all fields hooked is replaced with an appropriate language.
	 * Two arguments are supplied:
	 * - two-letter language code of active language to which the edit language is already switched.
	 * - the language code from which the edit language is being switched.
	 * The value of "this" is set to the only global instance of qTranslateX object.
	 */
	this.addLanguageSwitchAfterListener=function(func){ qTranslateConfig.onTabSwitchFunctionsLoad.push(func); }

	/**
	 * @since 3.2.9.8.9
	 * Designed as interface for other plugin integration. The documentation is available at
	 * https://qtranslatexteam.wordpress.com/integration/
	 * 
	 */
	this.enableLanguageSwitchingButtons=function(on){
		var display = on ? 'block' : 'none';
		for(var lang in qTranslateConfig.tabSwitches){
			var tabSwitches = qTranslateConfig.tabSwitches[lang];
			for(var i=0; i < tabSwitches.length; ++i){
				var tabSwitch = tabSwitches[i];
				var tabSwitchParent = tabSwitches[i].parentElement;
				tabSwitchParent.style.display = display;
				break;
			}
			break;
		}
	}

	this.getWrapForm=function(){
		var wraps = document.getElementsByClassName('wrap');
		for(var i=0; i < wraps.length; ++i){
			var w = wraps[i];
			var forms = w.getElementsByTagName('form');
			if(forms.length) return forms[0];
		}
		var forms = document.getElementsByTagName('form');
		if(forms.length === 1)
			return forms[0];
		for(var i=0; i < forms.length; ++i){
			var f = forms[i];
			wraps = f.getElementsByClassName('wrap');
			if(wraps.length) return f;
		}
		return null;
	}

	this.getFormWrap=function(){
		var forms = document.getElementsByTagName('form');
		for(var i=0; i < forms.length; ++i){
			var f = forms[i];
			var wraps = f.getElementsByClassName('wrap');
			if(wraps.length) return wraps[0];
		}
		var wraps = document.getElementsByClassName('wrap');
		for(var i=0; i < wraps.length; ++i){
			var w = wraps[i];
			forms = w.getElementsByTagName('form');
			if(forms.length) return w;
		}
		return null;
	}

	if( typeof(pg.addContentHooks) == "function")
		pg.addContentHooks(this);

	if( qTranslateConfig.page_config && qTranslateConfig.page_config.forms)
		this.addPageHooks(qTranslateConfig.page_config.forms);

	if(!displayHookNodes.length && !displayHookAttrs.length){
		var ok = false;
		for(var key in contentHooks){ ok = true; break; }
		if(!ok)
			return;
	}

	//create sets of LSB
	if(qTranslateConfig.LSB){
		var anchors=[];
		if(qTranslateConfig.page_config && qTranslateConfig.page_config.anchors){
			for(var i=0; i < qTranslateConfig.page_config.anchors.length; ++i){
				var anchor = qTranslateConfig.page_config.anchors[i];
				var f = document.getElementById(anchor);
				if(f) anchors.push(f);
			}
		}
		if(!anchors.length){
			var f=pg.langSwitchWrapAnchor;
			if(!f){
				f = this.getWrapForm();
			}
			if(!f){
				f = this.getWrapForm();
			}
			if(f) anchors.push(f);
		}
		for(var i=0; i < anchors.length; ++i){
			var anchor = anchors[i];
			var langSwitchWrap=qtranxj_ce('ul', {className: qTranslateConfig.lsb_style_wrap_class});
			anchor.parentNode.insertBefore( langSwitchWrap, anchor );
			var languageSwitch = new qtranxj_LanguageSwitch(langSwitchWrap);
		}
		/**
		 * @since 3.2.4 Synchronization of multiple sets of Language Switching Buttons
		 */
		this.addLanguageSwitchListener(onTabSwitch);
		if(pg.onTabSwitch){
			this.addLanguageSwitchListener(pg.onTabSwitch);
		}
	}
}

/**
 * @since 3.2.4 Multiple sets of Language Switching Buttons
 */
function qtranxj_LanguageSwitch(langSwitchWrap)
{
	//var langs=qTranslateConfig.enabled_languages, langNames=qTranslateConfig.language_name;
	var langs=qTranslateConfig.language_config;
	if(!qTranslateConfig.tabSwitches) qTranslateConfig.tabSwitches={};
	function switchTab()
	{
		var tabSwitch=this;
		if (!tabSwitch.lang){
			alert('qTranslate-X: This should not have happened: Please, report this incident to the developers: !tabSwitch.lang');
			return;
		}
		if ( qTranslateConfig.activeLanguage === tabSwitch.lang ){
			return;
		}
		if (qTranslateConfig.activeLanguage)
		{
			var tabSwitches = qTranslateConfig.tabSwitches[qTranslateConfig.activeLanguage];
			for(var i=0; i < tabSwitches.length; ++i){
				tabSwitches[i].classList.remove(qTranslateConfig.lsb_style_active_class);
				//tabSwitches[i].classList.remove('active');
				//tabSwitches[i].classList.remove('wp-ui-highlight');
			}
			//tabSwitches[qTranslateConfig.activeLanguage].classList.remove('active');
			var onTabSwitchFunctionsSave = qTranslateConfig.onTabSwitchFunctionsSave;
			for(var i=0; i<onTabSwitchFunctionsSave.length; ++i)
			{
				onTabSwitchFunctionsSave[i].call(qTranslateConfig.qtx,qTranslateConfig.activeLanguage,tabSwitch.lang);
			}
		}
		var langFrom = qTranslateConfig.activeLanguage;
		qTranslateConfig.activeLanguage=tabSwitch.lang;
		{
			var tabSwitches = qTranslateConfig.tabSwitches[qTranslateConfig.activeLanguage];
			for(var i=0; i < tabSwitches.length; ++i){
				tabSwitches[i].classList.add(qTranslateConfig.lsb_style_active_class);
				//tabSwitches[i].classList.add('active');
				//tabSwitches[i].classList.add('wp-ui-highlight');
			}
			//tabSwitch.classList.add('active');
		}
		var onTabSwitchFunctions = qTranslateConfig.onTabSwitchFunctions;
		for(var i=0; i<onTabSwitchFunctions.length; ++i)
		{
			onTabSwitchFunctions[i].call(qTranslateConfig.qtx,tabSwitch.lang,langFrom);
		}
		var onTabSwitchFunctionsLoad = qTranslateConfig.onTabSwitchFunctionsLoad;
		for(var i=0; i<onTabSwitchFunctionsLoad.length; ++i)
		{
			onTabSwitchFunctionsLoad[i].call(qTranslateConfig.qtx,tabSwitch.lang,langFrom);
		}
	}
	//location.pathname.indexOf();
	//for(var i=0; i<langs.length; ++i)
	for(var lang in langs)
	{
		var lang_conf = langs[lang];
		var flag_location=qTranslateConfig.flag_location;
		//var lang=langs[i];
		//var tabSwitch=qtranxj_ce ('li', {lang: lang, className: 'qtranxs-lang-switch', onclick: switchTab }, langSwitchWrap );
		//qtranxj_ce('img', {src: flag_location+qTranslateConfig.flag[lang]}, tabSwitch);
		//qtranxj_ce('span', {innerHTML: langNames[lang]}, tabSwitch);
		var tabSwitch=qtranxj_ce ('li', {lang: lang, className: 'qtranxs-lang-switch', onclick: switchTab }, langSwitchWrap );
		qtranxj_ce('img', {src: flag_location+lang_conf.flag}, tabSwitch);
		qtranxj_ce('span', {innerHTML: lang_conf.name}, tabSwitch);
		if ( qTranslateConfig.activeLanguage == lang )
			tabSwitch.classList.add(qTranslateConfig.lsb_style_active_class);
		if(!qTranslateConfig.tabSwitches[lang]) qTranslateConfig.tabSwitches[lang] = [];
		qTranslateConfig.tabSwitches[lang].push(tabSwitch);
	}
}

/**
 * qTranslateX instance is saved in global variable qTranslateConfig.qtx,
 * which can be used by theme or plugins to dynamically change content hooks.
 */
jQuery(document).ready(function($){ new qTranslateX(qTranslateConfig.js); });
