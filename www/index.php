<?php

$domain=ereg_replace('[^\.]*\.(.*)$','\1',$_SERVER['HTTP_HOST']);
$group_name=ereg_replace('([^\.]*)\..*$','\1',$_SERVER['HTTP_HOST']);
$themeroot='r-forge.r-project.org/themes/rforge/';

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en   ">

<head>
  <title>parfm: Parametric Frailty Models in R</title>

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="author" content="F. Rotolo and M. Munda" />
  <meta name="description" content="Parametric frailty models in R" />
  <meta name="keywords" content="parametric, frailty, frailty models, survival, R, package" /> 

  <link href="http://<?php echo $themeroot; ?>styles/estilo1.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="https://r-forge.r-project.org/themes/css/gforge.css" />
  <link rel="stylesheet" type="text/css" href="https://r-forge.r-project.org/themes/rforge/css/theme.css" />
</head>
<body>

<!-- R-Forge Logo -->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td>
<a href="http://r-forge.r-project.org/"><img src="http://<?php echo $themeroot; ?>/imagesrf/logo.png" border="0" alt="R-Forge Logo" /> </a> </td> </tr>
</table>


<!-- project title  -->

<h2>Parametric Frailty Models in R</h2>
<em>F.Rotolo, M.Munda</em>

<p>Frailty Models [<a href="#DJ08">1</a>,<a href="#W10">2</a>] are survival models for clustered or overdispersed duration data.
  They consist in proportional hazards Cox models [<a href="#C72">3</a>] with the addition of a random effect, accounting for different levels of risk
    due to unobserved covariates.
<p>According to the focus of the interest, estimation of parameters can be done by means of either a parametric or a semiparametric model.
  In the latter case, the baseline hazard is left unspecified and the penalized partial likelihood is considered [<a href="#TGP03">4</a>].
  Estimation can be done in R by means of the
    <a href="http://stat.ethz.ch/R-manual/R-patched/library/survival/html/coxph.html"><tt>coxph</tt></a> function 
    in the <a href="http://cran.r-project.org/web/packages/survival/index.html"><tt>survival</tt></a> package.

<p>In the case of parametric frailty models, estimation is based on the marginal loglikelihood.
  Here we provide <B>R</B> functions for many of the most common models.</br>
  Possible basline hazards are
  <ul> <li>Weibull,</li> <li>Exponential,</li> <li>Gompertz,</li> <li>LogNormal,</li> <li>LogLogistic.</li> </ul>
 Possible Frailty distributions are
  <ul> <li>Gamma,</li> <li>Inverse Gaussian,</li> <li>Positive Stable.</li> </ul>
The method is analogous to that of the Stata
  <a href = "http://www.stata.com/help.cgi?streg"><tt>streg</tt></a> command [<a href="#G02">5</a>],
  the difference being that <tt>streg</tt> command offers one more baseline (the generalized gamma survival distribution)
  and one less frailty distribution (the Positive Stable).


<h3>Parametrisations</h3>
<h4>Baseline hazards</h4>
<p>The <B>Exponential</B> model is</p>
  <p align="center"><i>h</i>(<i>t; &lambda;</i>)<i> = &lambda;</i>,</p>
  <p>with <i>&lambda;&gt;0</i>.</p>
<p>The <B>Weibull</B> model is</p>
  <p align="center"><i>h</i>(<i>t; &rho;, &lambda;</i>)<i> = &rho; &lambda; t<sup>&rho;-1</sup></i>,</p>
  <p>with <i>&rho;,&lambda;&gt;0</i>.</p>
<p>The <B>Gompertz</B> model is</p>
  <p align="center"><i>h</i>(<i>t; &gamma;, &lambda;</i>)<i> = &lambda; </i>e<i><sup>&gamma;t</sup></i>,</p>
  <p>with <i>&gamma;,&lambda;&gt;0</i>.</p>
<p>The <B>LogNormal</B> model is</p>
  <p align="center"><i>h</i>(<i>t; &mu;, &sigma;</i>) = 
    {<i> &phi;</i>([log<i> t -&mu;</i>]<i>/&sigma;</i>)}<i> / </i>{<i> &sigma; t </i>[<i>1-&Phi;</i>([log<i> t -&mu;</i>]<i>/&sigma;</i>)]},</p>
  <p>with <i>&mu;&in;<strong>R</strong></i>, <i>&sigma;&gt;0</i> and <i>&phi;</i>(<i>.</i>)<i></i> and <i>&Phi;</i>(<i>.</i>)<i></i> the density and distribution functions of a standard Normal.</p>
<p>The <B>LogLogistic</B> model is</p>
  <p align="center"><i>h</i>(<i>t; &alpha;, &kappa;</i>) = 
    {exp(<i>&alpha;</i>) <i>&kappa; t<sup>&kappa;-1</sup> } <i>/</i> {
      <i>1 +</i> exp(<i>&alpha;</i>) <i>t<sup>&kappa;</sup></i>},</p>
  <p>with <i>&alpha;&in;<strong>R</strong></i> and <i>&kappa;&gt;0</i>.</p>

<h4>Frailty distributions</h4>
<p>The <B>Gamma</B> model is</p>
  <p align="center"><i>f</i>(<i>u;, &theta;</i>) = 
    {<i> u<sup>1/&theta;-1</sup> </i>e<i><sup>-u/&theta;</sup> </i>}<i>/</i>{<i> &Gamma;</i>(<i>1/&theta;</i>)<i> &theta;<sup>1/&theta;</sup> </i>}<i></i>,</p>
  <p>with <i>&theta;&gt;0</i> and <i>&Gamma;</i>(<i>.</i>)<i></i> the Gamma function.</p>
<p>The <B>Inverse Gaussian</B> model is</p>
  <p align="center"><i>f</i>(<i>u; &theta;</i>) = 
    (<i>2&theta;&pi;</i>)<i><sup>-1/2</sup> u<sup>-3/2</sup> </i>exp<i></i>{<i> </i>(<i>u-1</i>)<i><sup>2</sup> / 2u&theta; </i>}<i></i>,</p>
  <p>with <i>&theta;&gt;0</i>.</p>
<p>The <B>Positive Stable</B> model is</p>
  <p align="center"><i>f</i>(<i>u; &theta;</i>)<i> = 
    -</i>&Sigma;<i><sub>k=1...&#8734;</sub></i>{<i>-u<sup>-&theta;k</sup> </i>sin<i></i>(<i>&theta;k&pi;</i>)<i> &Gamma;</i>(<i>k&theta;+1</i>)<i>/k!</i>}<i> / &pi;u</i>,</p>
  <p>with <i>&theta;&gt;0</i> and <i>&Gamma;</i>(<i>.</i>)<i></i> the Gamma function.</p>

<p><strong>Project summary</strong>:
  <a href="http://<?php echo $domain; ?>/projects/<?php echo $group_name; ?>/">here</a>. </p>


<!--References--><hr>
<h3>References</h3>
<p> [<a name="DJ08">1</a>] Duchateau, L. & Janssen, P. (2008) 
  <em><a href="http://www.springer.com/statistics/life+sciences,+medicine+%26+health/book/978-0-387-72834-6">The frailty model</a></em>.
  Springer.</p>
<p> [<a name="W10">2</a>] Wienke, A. (2010)
  <em><a href="http://dx.doi.org/10.1201/9781420073911">Frailty Models in Survival Analysis</a></em>.
  Chapman & Hall/CRC biostatistics series. Taylor and Francis.</p>
<p> [<a name="C72">3</a>] Cox, D. R. (1972)
  <a href="http://www.jstor.org/stable/2985181">Regression models and life-tables</a>. 
  <em>Journal of the Royal Statistical Society. Series B (Methodological)</em> 34, 187–220.</p>
<p> [<a name="TGP03">4</a>] Therneau, T. M., Grambsch, P. M. & Pankratz, V. S. (2003) 
  <a href="http://dx.doi.org/10.1198/1061860031365">Penalized survival models and frailty</a>.
  <em>Journal of Computational and Graphical Statistics</em> 12, 156–175.</p>
<p> [<a name="G02">5</a>] Gutierrez, R. G.  (2002)
  <a href="http://www.stata-journal.com/article.html?article=st0006">Parametric frailty and shared frailty survival models</a>. 
  <em>Stata Journal</em> 2(1), 22-44.</p>

</body>
</html>
