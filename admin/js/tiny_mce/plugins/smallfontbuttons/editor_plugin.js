/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('smallfontbuttons');

	tinymce.create('tinymce.plugins.smallfontbuttons', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			s = this.settings;
			this.settings = tinymce.extend({
				small_advanced_fonts : "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats",
				small_advanced_path : true,
				small_advanced_font_sizes : "1,2,3,4,5,6,7",
				font_size_style_values: "8pt,10pt,12pt,14pt,18pt,24pt,36pt"
			}, ed.settings);

			/*if (tinymce.is(ed.settings.small_advanced_font_sizes, 'string')) {

				s.font_size_style_values = tinymce.explode(s.font_size_style_values);
				s.font_size_classes = tinymce.explode(s.font_size_classes || '');

				// Parse string value
				o = {};
				ed.settings.theme_advanced_font_sizes = s.small_advanced_font_sizes;
				tinymce.each(ed.getParam('theme_advanced_font_sizes', '', 'hash'), function(v, k) {
					var cl;

					if (k == v && v >= 1 && v <= 7) {
						k = v + ' (' + t.sizes[v - 1] + 'pt)';

						if (ed.settings.convert_fonts_to_spans) {
							cl = s.font_size_classes[v - 1];
							v = s.font_size_style_values[v - 1] || (t.sizes[v - 1] + 'pt');
						}
					}

					if (/\s*\./.test(v))
						cl = v.replace(/\./g, '');

					o[k] = cl ? {'class' : cl} : {fontSize : v};
				});

				s.small_advanced_font_sizes = o;
			}*/
		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			switch (n) {
				case 'smallFontSize': return this.__createFontSizeBtn(cm);
				case 'smallFontFace': return this.__createFontFaceBtn(cm);
			}
			return null;
		},

		__createFontSizeBtn: function(cm) {

			var t = this, ed = t.editor, c, i = 0, cl = [];

			c = cm.createMenuButton('smallFontSize', {
										title : 'advanced.font_size',
										//image : 'images/fsize.png',
										icons : false
									});

			c.onRenderMenu.add(function(c, m) {
					tinymce.each(t.settings.small_advanced_font_sizes, function(v, k) {

						var fz = v.fontSize;

						if (fz >= 1 && fz <= 7)
								fz = t.sizes[parseInt(fz) - 1] + 'pt';

						m.add({
							title: k,
							'style' : 'font-size:' + fz, 'class' : 'mceFontSize' + (i++) + (' ' + (v['class'] || '')),
							onclick: function(){
									if (v.fontSize)
										ed.execCommand('FontSize', false, v.fontSize);
									else {
										each(t.settings.theme_advanced_font_sizes, function(v, k) {
											if (v['class'])
												cl.push(v['class']);
										});

										ed.editorCommands._applyInlineStyle('span', {'class' : v['class']}, {check_classes : cl});
									}
							}
						});
					});
			});

			return c;

		}
	});

	// Register plugin
	tinymce.PluginManager.add('smallfontbuttons', tinymce.plugins.smallfontbuttons);
})();