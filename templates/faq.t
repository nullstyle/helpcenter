{include file="header.t"}

<h1>FAQ</h1>

{include file="question-box.t"}

<div>

<h2>Frequently Asked Questions</h2>

{foreach from=$faqs key=i item=question}
<h5><a class="underlined" href="topic.php?id={$question.id|urlencode}">{$question.title}</a></h5>
<p class="tight">
{$question.content}
</p>
<p class="tight">
<a href="topic.php?id={$question.id}">See the full discussion</a>
</p>
<hr class="light" />
{/foreach}

<ul>

</ul>

</div>

{include file="footer.t"}
