<?php

/* @MT_s3files/event/posting_layout_include_panel_body.html */
class __TwigTemplate_75f1b1c907f2f3270484fd9f64bd6f1a861ec9092d4fcd5853215ba3030c4461 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $asset_file = "@MT_s3files/mytheme.css";
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('3');
        }
        $this->getEnvironment()->get_assets_bag()->add_stylesheet($asset);        // line 2
        echo "
";
        // line 3
        $asset_file = "@MT_s3files/w3.css";
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('3');
        }
        $this->getEnvironment()->get_assets_bag()->add_stylesheet($asset);        // line 4
        echo "
";
        // line 5
        $asset_file = "@MT_s3files/handleImgS3BBCode.js";
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('3');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 6
        echo "
";
        // line 7
        $asset_file = "@MT_s3files/image-compressor.js";
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('3');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 8
        echo "
";
        // line 9
        $asset_file = "@MT_s3files/watermark.js";
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('3');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 10
        echo "
<link href='https://fonts.googleapis.com/css?family=Alegreya Sans SC' rel='stylesheet'>

<div class=\"panel bg3 panel-container\" id=\"imgs3-panel\">
\t
\tIn questa finestra è possibile gestire gli allegati relativi a questo post.
\t
\t<br/>
\t<br/>
\t
\t<div id=\"container\" style=\"float:left;overflow-x:auto;white-space:nowrap;width:100% \">
\t\t<div style=\"display: inline-block;\">
\t\t\t<input type=\"button\" class=\"button2\" value=\"Aggiungi file\" id=\"s3addfiles\" onclick=\"upLoadNewFile(0)\" />
\t\t</div>
\t\t<div style=\" margin-left: 50px;display: inline-block;\">
\t\t\t
\t\t\t\t<input type=\"button\"   class=\"button2\" value=\"Aggiungi file con watermark\" id=\"s3addfiles_watermark\" onclick=\"upLoadNewFile(1)\" />
\t\t\t
\t\t\t
\t\t\t\t<label for=\"signature_watermark\">Firma</label>
\t\t\t\t<input type=\"text\" id=\"signature_watermark\" name=\"s3filelist\" maxlength=\"20\" value=\"";
        // line 30
        echo ($context["s3_username"] ?? null);
        echo "\" />
\t\t\t
\t\t\t\t\t<input type=\"radio\" name=\"gender\" value=\"female\"> Sinistra\t
  \t\t\t\t\t<input id=\"radio_watermark_rigth\" type=\"radio\" name=\"gender\" value=\"male\" checked=\"checked\"> Destra
  \t\t\t\t\t
\t\t\t
\t\t</div>
\t\t<br/>
\t\t
\t</div>
\t<br/>
\t<br/>
\t<br/>
\t<div id=\"loader\" style=\"display:none;margin: 0 auto;\"></div>
\t<input type=\"file\" id=\"imgupload\" style=\"display:none\" onChange=\"onFileChoosen()\" multiple=\"multiple\"/> 
\t
\t<input type=\"text\" id=\"s3filelist\" name=\"s3filelist\" style=\"display:none\" />
\t<input type=\"text\" id=\"flgwatermark\" name=\"flgwatermark\" style=\"display:none\" />
\t
\t<br/>
\t<br/>
\t
\t<input type=\"text\" id=\"s3username\" name=\"s3username\" style=\"display:none\" value=\"";
        // line 52
        echo ($context["s3_username"] ?? null);
        echo "\" />
\t
\t<input type=\"text\" id=\"oldrows\" value=\"";
        // line 54
        echo ($context["S_SOME_VARIABLE"] ?? null);
        echo "\" style=\"display:none\" name=\"oldrows\"/> 
\t
\t<input type=\"text\" id=\"quoted\" value=\"";
        // line 56
        echo ($context["S_QUOTED_FILES"] ?? null);
        echo "\" style=\"display:none\" name=\"quoted\"/> 
\t
\t<div class=\"panel file-list-container \" style=\"display:none; overflow-x:auto;\"  id=\"s3-file-list-container\">
\t\t\t<table id=\"s3table\" class=\" zebra-list w3-table css-serial s3table\" name=\"s3table\" >
  
\t\t\t<col width=\"5%\">
\t\t\t<col width=\"15%\">
\t\t\t<col width=\"20%\">
\t\t\t<col width=\"25%\">
\t\t\t<col width=\"10%\">
\t\t\t<col width=\"15%\">
\t\t\t<col width=\"10%\">
\t\t\t<thead>
\t\t\t\t<tr>
\t\t\t\t\t<th>N°</th>
\t\t\t\t\t<th class=\"attach-name\">NOME</th>
\t\t\t\t\t<th class=\"attach-name\">ANTEPRIMA</th>
\t\t\t\t\t<th class=\"attach-name\">DIDASCALIA</th>
\t\t\t\t\t<th class=\"attach-filesize\">DIMENSIONE</th>
\t\t\t\t</tr>
\t\t\t</thead>
\t\t\t
\t\t\t</table>

\t</div>
\t
\t
</div>

<script>
\t
\t//build names of quoted files
\tvar quotedNames=[];
\tif (document.getElementById(\"quoted\").value.length>1) {
\t\tvar stringRows = document.getElementById(\"quoted\").value.substring(1).split(\"*\");
\t\tfor (var j=0;j<stringRows.length;j++) {
\t\t    var rowString = stringRows[j].split(\"#\");
\t\t\tvar name = rowString[0];
\t\t\tquotedNames.push(name);
\t\t}
\t}
\t
\t//build rows of added files
\tvar names=[];
\t
\tif (document.getElementById(\"oldrows\").value.length>1) {
\t\t
\t\tvar table = document.getElementById(\"s3table\");
\t\t
\t
\t\tvar stringRows = document.getElementById(\"oldrows\").value.substring(1).split(\"*\");
\t\t
\t\tfor (var j=0;j<stringRows.length;j++) {
\t\t\t (function () {
\t\t\t\tvar rowString = stringRows[j].split(\"#\");
\t\t\t\tvar name = rowString[0];
\t\t\t\tvar size = rowString[2];
\t\t\t\tvar id = rowString[1];
\t\t\t\tvar tag = rowString[3];
\t\t\t\tvar didascalia = rowString[4];
\t\t\t\t
\t\t\t\tvar row = table.insertRow();
\t\t\t\t
\t\t\t\trow.heigth=\"100\";
\t\t
\t\t\t\t//number
\t\t\t\tvar cell0 = row.insertCell(0);
\t\t\t\t
\t\t\t\t//nome
\t\t\t\tvar cell1 = row.insertCell(1);
\t\t\t\t
\t\t\t\t//anteprima
\t\t\t\tvar cell5 =row.insertCell(2);
\t\t\t\t
\t\t\t\t//didascalia
\t\t\t\tvar cell6 = row.insertCell(3);
\t\t\t\t
\t\t\t\t
\t\t\t\t
\t\t\t\tif (tag == 'image') {
\t\t\t\t\tvar img = document.createElement('img');
\t\t\t\t\timg.src = \"./ext/MT/s3files/download/linkAllegato.php?id=\"+id+\"&tag=image\";
\t\t\t\t\timg.style.maxHeight=\"80px\";
\t\t\t\t\timg.style.maxWidth=\"100px\";
\t\t\t\t\tcell5.appendChild(img);
\t\t\t\t\t
\t\t\t\t\tvar didascaliaInput=document.createElement(\"input\");
\t\t\t\t\tdidascaliaInput.disabled=true;
\t\t\t\t\tdidascaliaInput.value=didascalia;
\t\t\t\t\tdidascaliaInput.type = \"text\";
\t\t\t\t\tdidascaliaInput.size=65;
\t\t\t\t\tdidascaliaInput.maxLength=75;
\t\t\t\t\tdidascaliaInput.addEventListener(\"keyup\",function checkText() {
\t\t\t\t\t\trestrictDidascaliaInput(didascaliaInput);
\t\t\t\t\t});
\t\t\t\t\t
\t\t\t\t\tvar editSaveDidascaliaButton=document.createElement('button');
\t\t\t\t\teditSaveDidascaliaButton.innerHTML='<img src=\"./ext/MT/s3files/images/edit.png\" width=\"30px\" />';
\t\t\t\t\teditSaveDidascaliaButton.addEventListener(\"click\",function _edit() {
\t\t\t\t\t\teditDidascalia(didascaliaInput,editSaveDidascaliaButton,name,row,id,size,tag);
\t\t\t\t\t});
\t\t\t\t\teditSaveDidascaliaButton.type=\"button\";
\t\t\t\t\tcell6.appendChild(didascaliaInput);
\t\t\t\t\tcell6.appendChild(editSaveDidascaliaButton);
\t\t\t\t}
\t\t\t\t
\t\t\t\t
\t\t\t\t//dimensione
\t\t\t\tvar cell2 = row.insertCell(4);
\t\t\t\t
\t\t\t\t//aggiungi in linea
\t\t\t\tvar cell3 = row.insertCell(5);
\t\t
\t\t\t\t//cancella
\t\t\t\tvar cell4 = row.insertCell(6);
\t\t\t\t
\t
\t\t\t\tcell1.innerHTML = name;
\t\t\t\t
\t\t\t\tcell2.innerHTML = size+\" KB\" ; 
\t\t\t\t
\t\t\t\tvar btn1 = document.createElement('input');
\t\t\t\tbtn1.style.width = \"100px\";
\t\t\t\tbtn1.style.height = \"60px\";
\t\t\t\tbtn1.type = \"button\";
\t\t\t\tbtn1.style.display = \"block\";
\t\t\t\tbtn1.className = \"btn\";
\t\t\t\tbtn1.value = \"Aggiungi in linea\";
\t\t\t\tbtn1.addEventListener(\"click\", function() {
\t\t\t\t\taggiungiInLinea(name,tag,id);
\t\t\t\t});
\t\t\t\tcell3.appendChild(btn1);
\t\t\t\t
\t\t\t\t
\t\t\t\tvar btn2 = document.createElement('input');
\t\t\t\tbtn2.style.width = \"100px\";
\t\t\t\tbtn2.style.height = \"60px\";
\t\t\t\tbtn2.type = \"button\";
\t\t\t\tbtn2.className = \"btn\";
\t\t\t\tbtn2.value = \"Cancella\";
\t\t\t\tbtn2.addEventListener(\"click\",function() {
\t\t\t\t\tcancella(name,row,tag,id,size,didascaliaInput);
\t\t\t\t});
\t\t\t\tcell4.appendChild(btn2);
\t\t\t\t
\t\t\t\t
\t\t\t\t
\t\t\t\t
\t\t\t\t
\t\t\t\tnames.push(name);
\t\t\t\tdocument.getElementById(\"s3filelist\").value=document.getElementById(\"s3filelist\").value+\"*\"+name+\"#\"+id+\"#\"+size+\"#\"+tag+\"#\"+didascalia;
\t\t\t\tdocument.getElementById(\"s3-file-list-container\").style.display = \"block\";
\t\t\t }()); 
\t\t}
\t}
\t
\t

</script>";
    }

    public function getTemplateName()
    {
        return "@MT_s3files/event/posting_layout_include_panel_body.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  143 => 56,  138 => 54,  133 => 52,  108 => 30,  86 => 10,  75 => 9,  72 => 8,  61 => 7,  58 => 6,  47 => 5,  44 => 4,  33 => 3,  30 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@MT_s3files/event/posting_layout_include_panel_body.html", "");
    }
}
