<?php
get_header(); ?>
<link href="<?php echo THEME_URI;?>/highlight/styles/shCore.css" rel="stylesheet" type="text/css"/> 
<link href="<?php echo THEME_URI;?>/highlight/styles/shThemeDefault.css" rel="stylesheet" type="text/css"/> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shCore.js" type="text/javascript"></script> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushCpp.js" type="text/javascript"></script> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushCSharp.js" type="text/javascript"></script> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushCss.js" type="text/javascript"></script> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushJava.js" type="text/javascript"></script> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushDelphi.js" type="text/javascript"></script> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushRuby.js" type="text/javascript"></script> 
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushBash.js" type="text/javascript"></script>
<script src="<?php echo THEME_URI;?>/highlight/scripts/shBrushPython.js" type="text/javascript"></script> 
<script language='javascript'> 
SyntaxHighlighter.config.bloggerMode = false;
SyntaxHighlighter.config.clipboardSwf = "<?php echo THEME_URI;?>/highlight/scripts/clipboard.swf";
SyntaxHighlighter.all();
</script>

<div id="content">
			
<div class="faq-left">
<div class="hentry">	
			<h2>Q:What is the compiler the judge is using and what are the compiler options?</h2>
			 The online judge system is running on <a href="http://www.debian.org/">Debian Linux</a>. We are using <a href="http://gcc.gnu.org/">GNU GCC/G++</a> for C/C++ compile, <a href="http://www.freepascal.org">Free Pascal</a> for pascal compile and <a href="http://www.oracle.com/technetwork/java/index.html">sun-java-jdk1.6</a> for Java. The compile options are:<br>
			<table border="1">
			  <tbody><tr>
			    <td>C:</td>
			    <td><font color="blue">gcc Main.c -o Main -Wall -lm --static -std=c99 -DONLINE_JUDGE</font></td>
			  </tr>
			  <tr>
			    <td>C++:</td>
			    <td><font color="blue">g++ Main.cc -o Main -O2 -Wall -lm --static -DONLINE_JUDGE</font></td>
			  </tr>
			  <tr>
			    <td>Pascal:</td>
			    <td><font color="blue">fpc -Co -Cr -Ct -Ci</font></td>
			  </tr>
			  <tr>
			    <td>Java:</td>
			    <td><font color="blue">javac -J-Xms32m -J-Xmx256m Main.java</font>
			    <br>
			    <font color="red" size="-1">*Java has 2 more seconds and 512M more memory when running and judging.</font>
			    </td>
			  </tr>
			</tbody></table>
				<p>  Our compiler software version:<br>
			  <font color="blue">gcc (Ubuntu/Linaro 4.4.4-14ubuntu5) 4.4.5</font><br>
			  <font color="blue">glibc 2.3.6</font><br>
			<font color="blue">Free Pascal Compiler version 2.4.0-2 [2010/03/06] for i386<br>
			java version "1.6.0_22"<br>
			</font></p>	
</div>
<div class="hentry">
		<h2>Q:What is the meaning of the judge's reply XXXXX?</h2>
		Here is a list of the judge's replies and their meaning:
		<ul>
		<li><span class="PD">Pending</span>: The judge is so busy that it can't judge your submit at the moment, usually you just need to wait a minute and your submit will be judged.</li>
		<li><span class="PR">Pending Rejudge</span>: The test datas has been updated, and the submit will be judged again and all of these submission was waiting for the Rejudge.</li>
		<li><span class="CI">Compiling</span>: The judge is compiling your source code.</li>
		<li><span class="RJ">Running &amp; Judging</span>: Your code is running and being judging by our Online Judge.</li>
		<li><span class="AC">Accepted</span>: OK! Your program is correct!.</li>
		<li><span class="RE">Presentation Error</span>: Your output format is not exactly the same as the judge's output, although your answer to the problem is correct. Check your output for spaces, blank lines,etc against the problem output specification.</li>
		<li><span class="WA">Wrong Answer</span>: Correct solution not reached for the inputs. The inputs and outputs that we use to test the programs are not public (it is recomendable to get accustomed to a true contest dynamic ;-).</li>
		<li><span class="TLE">Time Limit Exceeded</span>: Your program tried to run during too much time.</li>
		<li><span class="MLE">Memory Limit Exceeded</span>: Your program tried to use more memory than the judge default settings. </li>
		<li><span class="OLE">Output Limit Exceeded</span>: Your program tried to write too much information. This usually occurs if it goes into a infinite loop. Currently the output limit is 1M bytes.</li>
		<li><span class="RE">Runtime Error</span>: All the other Error on the running phrase will get Runtime Error, such as 'segmentation fault','floating point exception','used forbidden functions', 'tried to access forbidden memories' and so on.</li>
		<li><span class="CE">Compile Error</span>: The compiler (gcc/g++/gpc) could not compile your ANSI program. Of course, warning messages are not error messages. Click the link at the judge reply to see the actual error message.</li>
		</ul>
</div>
</div>

<div class="faq-right">
<div class="hentry">
		<h2>Q:Where is the input and the output?</h2>
		Your program shall read input from stdin('Standard Input') and write output to stdout('Standard Output').For example,you can use 'scanf' in C or 'cin' in C++ to read from stdin,and use 'printf' in C or 'cout' in C++ to write to stdout.
		User programs are not allowed to open and read from/write to files, you will get a "Runtime Error" if you try to do so.
		
		<ul>
		<li>Here is a sample solution for problem 1000 using C++:
		<pre class="brush:c++">
		#include &lt;iostream&gt;
		using namespace std;
		int main(){
		    int a,b;
		    while(cin &gt;&gt; a &gt;&gt; b)
		        cout &lt;&lt; a+b &lt;&lt; endl;
			return 0;
		}</pre></li><li>
		Here is a sample solution for problem 1000 using C:
		<pre class="brush:c">
		#include &lt;stdio.h&gt;
		int main(){
		    int a,b;
		    while(scanf("%d %d",&amp;a, &amp;b) != EOF)
		        printf("%d\n",a+b);
			return 0;
		}
		</pre></li><li>
		Here is a sample solution for problem 1000 using PASCAL:
		<pre class="brush:pascal"> 
		program p1001(Input,Output); 
		var 
		  a,b:Integer; 
		begin 
		   while not eof(Input) do 
		     begin 
		       Readln(a,b); 
		       Writeln(a+b); 
		     end; 
		end.</pre></li><li>
		Here is a sample solution for problem 1000 using Java:
		 <pre class="brush:java">
		import java.util.*;
		public class Main{
			public static void main(String args[]){
				Scanner cin = new Scanner(System.in);
				int a, b;
				while (cin.hasNext()){
					a = cin.nextInt(); b = cin.nextInt();
					System.out.println(a + b);
				}
			}
		}</pre></li>
	</ul>
</div>

<div class="hentry">
		<h2>Q:Why did I get a Compile Error? It's well done!</h2>
		There are some differences between GNU and MS-VC++, such as:
		<ul>
		  <li><font color="blue">main</font> must be declared as <font color="blue">int</font>, <font color="blue">void main</font> will end up with a Compile Error.<br> 
		  </li><li><font color="green">i</font> is out of definition after block "<font color="blue">for</font>(<font color="blue">int</font> <font color="green">i</font>=0...){...}"<br>
		  </li><li><font color="green">itoa</font> is not an ANSI function.<br>
		  </li><li><font color="green">__int64</font> of VC is not ANSI, but you can use <font color="blue">long long</font> for 64-bit integer.<br>
		</li></ul>
</div>

<div class="hentry">
		<h2>Q:How to attend Online Contests?</h2>
		<p>	Can you submit programs for any practice problems on this Online Judge? If you can, then that is the account you use in an online contest. If you can't, then please register an id with password first.
		</p>
</div>



</div>






		
		
		
	</div><!-- #content -->
	<?php do_atomic( 'after_content' ); // retro-fitted_after_content ?>
	
	<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>

	<?php get_sidebar( 'secondary' ); // Loads the sidebar-secondary.php template. ?>

	<?php do_atomic( 'close_main' ); // retro-fitted_close_main ?>
<?php get_footer(); ?>
