<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Note</title>
	<style>
		button {
			cursor: pointer;
			padding: 8px 16px;
			font-size: 14px;
			border: 1px solid #037aff;
			border-radius: 4px;
			background-color: #fff;
			color: #037aff;
			margin-right: 10px;
			margin-bottom: 10px;
			outline: none;
		}
		button:hover {
			background-color: #037aff;
			color: #fff;
		}

		.md-preview pre {
			overflow-x: scroll;
			white-space: pre-wrap;
			white-space: -moz-pre-wrap;
			white-space: -pre-wrap;
			white-space: -o-pre-wrap;
			word-wrap: break-word;
			max-width: 100%;
			font-family: 'Courier New', Courier, monospace;
			font-size: 14px;
			line-height: 1.5;
			background-color: #f5f5f5;
			border: 1px solid #ccc;
			border-radius: 4px;
			padding: 10px;
			margin-bottom: 20px;
			color: #333;
		}
		.md-preview code {
			font-family: 'Courier New', Courier, monospace;
		}
		.md-preview blockquote {
			padding-left: 10px;
			border-left: 4px solid #ccc;
			margin-left: 0;
		}
		.md-preview table {
			border: 1px solid #ccc;
			border-collapse: collapse;
			margin-bottom: 20px;
		}
		.md-preview td, th {
			padding: 5px;
			border: 1px solid #ccc;
		}

		table.wrap {
			width: 100%;
			border-collapse: collapse;
		}
		.md-editor {
			width: 50%;
			box-sizing: border-box;
			padding: 20px;
		}
		.md-editor textarea, .md-editor input {
			width: 100%;
			box-sizing: border-box;
			font-family: 'Courier New', Courier, monospace;
			font-size: 14px;
			line-height: 1.5;
			background-color: #fff;
			border: 1px solid #ccc;
			border-radius: 4px;
			padding: 10px;
			margin-bottom: 20px;
			color: #333;
		}
	</style>
</head>
<body>
	<h1>Note</h1>
	<?php foreach( $list as $index => $note ) { ?>
		<div>
			<h2><a href="/note/<?= $note['id'] ?>"><?= $note['title'] ?></a></h2>
			<!-- <p><?= $note['content'] ?></p> -->
		</div>
	<?php } ?>
	<div class="new">
		<table class="wrap">
			<tr>
				<td class="md-editor">
					<form action="/note/save" method="post">
						<input type="text" name="title" placeholder="标题"><br />
						<textarea name="content" placeholder="内容"></textarea>
						<button type="submit">保存</button>
						<input type="hidden" name="id" value="0">
					</form>
				</td>
				<td class="md-preview"></td>
			</tr>
		</table>
	</div>
	<script src="/static/marked.min.js"></script>
	<script>
		let md = document.querySelector('textarea').value

		function markdownToHtml(markdownText) {
			const parser = new DOMParser();
			const doc = parser.parseFromString(markdownText, 'text/markdown');
			const html = doc.body.innerHTML;
			return html;
		}

		document.querySelector('textarea').addEventListener('input', function() {
			md = this.value
			document.querySelector('.md-preview').innerHTML = marked.parse(md);
		})

		// document.querySelector('td').innerHTML = markdownToHtml(md)
		document.querySelector('.md-preview').innerHTML = marked.parse(md);

	</script>
</body>
</html>
