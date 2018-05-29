<?php

/* @MT_s3files/event/posting_editor_add_panel_tab.html */
class __TwigTemplate_1e9074f5c712aa6ab2270910aff1938cef8b1cea93298b2b9cfd00e98d6bbc84 extends Twig_Template
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
        if (($context["s3_is_visible"] ?? null)) {
            // line 2
            echo "<li id=\"imgs3-panel-tab\" class=\"tab\">
\t<a href=\"#tabs\" data-subpanel=\"imgs3-panel\" role=\"tab\" aria-controls=\"imgs3-panel\" >Files</a>
</li>
";
        }
        // line 6
        echo "
";
    }

    public function getTemplateName()
    {
        return "@MT_s3files/event/posting_editor_add_panel_tab.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  27 => 6,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@MT_s3files/event/posting_editor_add_panel_tab.html", "");
    }
}
