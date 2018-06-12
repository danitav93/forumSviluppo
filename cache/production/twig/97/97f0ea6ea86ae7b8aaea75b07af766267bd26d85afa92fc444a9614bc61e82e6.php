<?php

/* s3_body.html */
class __TwigTemplate_d6e0dbcb12bbd14ce3f8732f5510ed653cf345079daa1886e7bf8fcc313d0478 extends Twig_Template
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
        $location = "overall_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("overall_header.html", "s3_body.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<h1>";
        // line 3
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SETTINGS");
        echo "</h1>

<p>";
        // line 5
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ACP_S3_ACCESS_KEYS_EXPLAIN");
        echo "</p>

";
        // line 7
        if (($context["S3_ERROR"] ?? null)) {
            // line 8
            echo "<div class=\"errorbox\"><h3>";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("WARNING");
            echo "</h3>
\t<p>";
            // line 9
            echo ($context["S3_ERROR"] ?? null);
            echo "</p>
</div>
";
        }
        // line 12
        echo "
<form id=\"acp_board\" method=\"post\" action=\"";
        // line 13
        echo ($context["U_ACTION"] ?? null);
        echo "\">
\t<fieldset>
\t\t<dl>
\t\t\t<dt>
\t\t\t\t<label for=\"s3_aws_access_key_id\">";
        // line 17
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ACP_S3_AWS_ACCESS_KEY_ID");
        echo "</label>
\t\t\t\t<br>
\t\t\t\t<span>";
        // line 19
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ACP_S3_AWS_ACCESS_KEY_ID_EXPLAIN");
        echo "</span>
\t\t\t</dt>
\t\t\t<dd>
\t\t\t\t<input type=\"text\" id=\"s3_aws_access_key_id\" name=\"s3_aws_access_key_id\" value=\"";
        // line 22
        echo ($context["S3_AWS_ACCESS_KEY_ID"] ?? null);
        echo "\"
\t\t\t\t\t   maxlength=\"255\" size=\"40\"/>
\t\t\t</dd>
\t\t</dl>

\t\t<dl>
\t\t\t<dt>
\t\t\t\t<label for=\"s3_aws_secret_access_key\">";
        // line 29
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ACP_S3_AWS_SECRET_ACCESS_KEY");
        echo "</label>
\t\t\t\t<br>
\t\t\t\t<span>";
        // line 31
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ACP_S3_AWS_SECRET_ACCESS_KEY_EXPLAIN");
        echo "</span>
\t\t\t</dt>
\t\t\t<dd>
\t\t\t\t<input type=\"text\" id=\"s3_aws_secret_access_key\" name=\"s3_aws_secret_access_key\"
\t\t\t\t\t   value=\"";
        // line 35
        echo ($context["S3_AWS_SECRET_ACCESS_KEY"] ?? null);
        echo "\" maxlength=\"255\" size=\"50\"/>
\t\t\t</dd>
\t\t</dl>

\t\t<dl>
\t\t\t<dt>
\t\t\t\t<label for=\"s3_region\">";
        // line 41
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ACP_S3_REGION");
        echo "</label>
\t\t\t\t<br>
\t\t\t\t<span>";
        // line 43
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ACP_S3_REGION_EXPLAIN");
        echo "</span>
\t\t\t</dt>
\t\t\t<dd>
\t\t\t\t<input type=\"text\" id=\"s3_region\" name=\"s3_region\" value=\"";
        // line 46
        echo ($context["S3_REGION"] ?? null);
        echo "\" maxlength=\"255\" size=\"30\"/>
\t\t\t</dd>
\t\t</dl>

\t\t<dl>
\t\t\t<dt>
\t\t\t\t<label for=\"s3_bucket\">";
        // line 52
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ACP_S3_BUCKET");
        echo "</label>
\t\t\t\t<br>
\t\t\t\t<span>";
        // line 54
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ACP_S3_BUCKET_EXPLAIN");
        echo "</span>
\t\t\t</dt>
\t\t\t<dd>
\t\t\t\t<input type=\"text\" id=\"s3_bucket\" name=\"s3_bucket\" value=\"";
        // line 57
        echo ($context["S3_BUCKET"] ?? null);
        echo "\" maxlength=\"255\" size=\"30\"/>
\t\t\t</dd>
\t\t</dl>

\t\t<hr/>

\t\t<dl>
\t\t\t<dt>
\t\t\t\t<label>";
        // line 65
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ACP_S3_IS_ENABLED");
        echo "</label>
\t\t\t\t<br>
\t\t\t\t<span>";
        // line 67
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ACP_S3_IS_ENABLED_EXPLAIN");
        echo "</span>
\t\t\t</dt>
\t\t\t<dd>";
        // line 69
        echo ($context["S3_IS_ENABLED"] ?? null);
        echo "</dd>
\t\t</dl>

\t\t<p class=\"submit-buttons\">
\t\t\t<input class=\"button1\" type=\"submit\" id=\"submit\" name=\"submit\" value=\"";
        // line 73
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SUBMIT");
        echo "\"/>&nbsp;
\t\t\t<input class=\"button2\" type=\"reset\" id=\"reset\" name=\"reset\" value=\"";
        // line 74
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("RESET");
        echo "\"/>
\t\t</p>
\t\t
\t\t";
        // line 77
        if (($context["S3_IS_ENABLED"] ?? null)) {
            // line 78
            echo "\t\t<hr/>
\t\t<dl>
\t\t\t<dt>
\t\t\t\t<label for=\"file_totali\">File totali</label>
\t\t\t\t<br>
\t\t\t\t<span>Numero di file presenti nella repository remota, file non orfani</span>
\t\t\t</dt>
\t\t\t<dd>
\t\t\t\t<input type=\"text\" id=\"file_totali\" name=\"file_totali\" value=\"";
            // line 86
            echo ($context["file_totali"] ?? null);
            echo "\" maxlength=\"10\" size=\"20\" readonly/>
\t\t\t</dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt>
\t\t\t\t<label for=\"peso_totale\">Peso totale s3</label>
\t\t\t\t<br>
\t\t\t\t<span>Il valore indica la somma delle dimensioni di tutti i file presenti sulla repository remota, ovvero gli allegati non orfani</span>
\t\t\t</dt>
\t\t\t<dd>
\t\t\t\t<input type=\"text\" id=\"peso_totale\" name=\"peso_totale\" value=\"";
            // line 96
            echo ($context["peso_totale"] ?? null);
            echo "\" maxlength=\"10\" size=\"20\" readonly/>
\t\t\t</dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt>
\t\t\t\t<label for=\"orfani_totali\">File orfani</label>
\t\t\t\t<br>
\t\t\t\t<span>Numero di file orfani presenti</span>
\t\t\t</dt>
\t\t\t<dd>
\t\t\t\t<input type=\"text\" id=\"orfani_totali\" name=\"orfani_totali\" value=\"";
            // line 106
            echo ($context["orfani_totali"] ?? null);
            echo "\" maxlength=\"10\" size=\"20\" readonly/>
\t\t\t</dd>
\t\t</dl>
\t\t<dl>
\t\t\t<dt>
\t\t\t\t<label for=\"peso_orfani\">Peso file orfani</label>
\t\t\t\t<br>
\t\t\t\t<span>Peso totale dei file orfani</span>
\t\t\t</dt>
\t\t\t<dd>
\t\t\t\t<input type=\"text\" id=\"peso_orfani\" name=\"peso_orfani\" value=\"";
            // line 116
            echo ($context["peso_orfani"] ?? null);
            echo "\" maxlength=\"10\" size=\"20\" readonly/>
\t\t\t</dd>
\t\t</dl>
\t\t<p class=\"submit-buttons\">
\t\t\t<input class=\"button1\" type=\"submit\" id=\"elimina_orfani\" name=\"elimina_orfani\" value=\"Elimina file orfani\"/>
\t\t</p>
\t\t
\t\t";
        }
        // line 124
        echo "\t\t
\t\t";
        // line 125
        echo ($context["S_FORM_TOKEN"] ?? null);
        echo "
\t</fieldset>
\t
\t
\t
</form>

";
        // line 132
        $location = "overall_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("overall_footer.html", "s3_body.html", 132)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "s3_body.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  253 => 132,  243 => 125,  240 => 124,  229 => 116,  216 => 106,  203 => 96,  190 => 86,  180 => 78,  178 => 77,  172 => 74,  168 => 73,  161 => 69,  156 => 67,  151 => 65,  140 => 57,  134 => 54,  129 => 52,  120 => 46,  114 => 43,  109 => 41,  100 => 35,  93 => 31,  88 => 29,  78 => 22,  72 => 19,  67 => 17,  60 => 13,  57 => 12,  51 => 9,  46 => 8,  44 => 7,  39 => 5,  34 => 3,  31 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "s3_body.html", "");
    }
}
