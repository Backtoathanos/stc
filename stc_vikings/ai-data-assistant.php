<?php
require_once 'kattegat/auth_helper.php';
STCAuthHelper::checkAuth();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AI Database Chat - STC</title>
    <link rel="icon" type="image/png" href="images/stc_logo_title.png">
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .dbc-page { max-width: 960px; margin: 0 auto; }
        .dbc-status {
            font-size: 13px; padding: 8px 12px; border-radius: 6px; margin-bottom: 12px;
        }
        .dbc-status.ok { background: #d4edda; color: #155724; }
        .dbc-status.bad { background: #f8d7da; color: #721c24; }
        .dbc-status.warn { background: #fff3cd; color: #856404; }

        .dbc-chat {
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #f9f9f9;
            min-height: 480px;
            max-height: 65vh;
            overflow-y: auto;
            padding: 16px;
            margin-bottom: 14px;
        }
        .dbc-empty { color: #888; text-align: center; padding: 40px 20px; font-size: 14px; }

        .dbc-msg { margin-bottom: 16px; clear: both; }
        .dbc-msg-user .dbc-bubble {
            background: #1976d2; color: #fff;
            margin-left: 15%; border-radius: 12px 12px 4px 12px;
        }
        .dbc-msg-ai .dbc-bubble {
            background: #fff; border: 1px solid #e0e0e0;
            margin-right: 5%; border-radius: 12px 12px 12px 4px;
        }
        .dbc-bubble {
            padding: 12px 14px;
            font-size: 14px;
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .dbc-label { font-size: 11px; color: #888; margin-bottom: 4px; font-weight: 600; }

        .dbc-sql {
            margin-top: 10px;
            background: #263238;
            color: #aed581;
            padding: 10px;
            border-radius: 6px;
            font-family: Consolas, monospace;
            font-size: 12px;
            overflow-x: auto;
        }
        .dbc-exec-ok { color: #2e7d32; font-size: 13px; margin-top: 8px; font-weight: 600; }
        .dbc-exec-err { color: #c62828; font-size: 13px; margin-top: 8px; }

        .dbc-table-wrap { margin-top: 10px; overflow-x: auto; max-height: 320px; overflow-y: auto; }
        .dbc-table-wrap table {
            width: 100%; border-collapse: collapse; font-size: 12px; background: #fff;
        }
        .dbc-table-wrap th, .dbc-table-wrap td {
            border: 1px solid #ddd; padding: 6px 8px; text-align: left;
        }
        .dbc-table-wrap th { background: #eceff1; position: sticky; top: 0; }

        .dbc-input-area {
            border: 1px solid #ccc;
            border-radius: 10px;
            background: #fff;
            padding: 12px;
        }
        .dbc-input-area textarea {
            width: 100%;
            border: none;
            resize: vertical;
            min-height: 80px;
            font-size: 15px;
            outline: none;
        }
        .dbc-input-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
        }
        .dbc-hint { font-size: 12px; color: #888; }
        .dbc-submit {
            background: #1976d2;
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
        }
        .dbc-submit:disabled { opacity: 0.6; cursor: not-allowed; }
        .dbc-submit:hover:not(:disabled) { background: #1565c0; }
        .dbc-thinking { color: #888; font-style: italic; padding: 8px 0; }
    </style>
</head>
<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    <?php include_once("header-nav.php"); ?>
    <div class="app-main">
        <?php include_once("sidebar-nav.php"); ?>
        <div class="app-main__outer">
            <div class="app-main__inner">
                <div class="dbc-page">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-1">AI Database Chat</h4>
                            <p class="text-muted mb-2" style="font-size:13px;">
                                Table ke records dekho, column add/remove karo, data update karo — sab chat se. (Local Ollama)
                            </p>
                            <div id="dbc-status" class="dbc-status warn">Connecting…</div>

                            <!-- Chat history -->
                            <div id="dbc-chat" class="dbc-chat">
                                <div class="dbc-empty" id="dbc-empty">
                                    Yahan chat history dikhegi.<br><br>
                                    Examples:<br>
                                    • <em>stc_product table ke 20 records dikhao</em><br>
                                    • <em>stc_merchant me phone column add karo VARCHAR(20)</em><br>
                                    • <em>stc_product id 5 ka name "Cement Ultra" update karo</em>
                                </div>
                            </div>

                            <!-- Input -->
                            <div class="dbc-input-area">
                                <textarea id="dbc-input" placeholder="Apna sawal likho… (Ctrl+Enter = Submit)"></textarea>
                                <div class="dbc-input-actions">
                                    <span class="dbc-hint">Submit dabao — AI SQL banayega aur khud chalayega</span>
                                    <button type="button" class="dbc-submit" id="dbc-submit">
                                        <i class="fa fa-paper-plane"></i> Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
(function () {
    var api = 'kattegat/ai_local_assistant_api.php';
    var history = [];
    var busy = false;

    function esc(s) {
        return $('<div>').text(s == null ? '' : String(s)).html();
    }

    function setStatus(html, cls) {
        $('#dbc-status').removeClass('ok bad warn').addClass(cls).html(html);
    }

    function ping() {
        $.ajax({
            url: api,
            method: 'POST',
            data: { ai_ping: 1 },
            dataType: 'json',
            xhrFields: { withCredentials: true },
            success: function (r) {
                if (!r.success) {
                    setStatus(esc(r.message || 'API error'), 'bad');
                    return;
                }
                var t = '<strong>Ollama OK</strong> &nbsp;|&nbsp; Model: ' + esc(r.model) + ' &nbsp;|&nbsp; Tables: ' + (r.table_count || 0);
                if (!r.model_ready) {
                    setStatus(t + ' &nbsp;—&nbsp; Run: <code>ollama pull ' + esc(r.model) + '</code>', 'warn');
                } else {
                    setStatus(t + (r.auto_execute ? ' &nbsp;|&nbsp; Auto-run ON' : ''), 'ok');
                }
            },
            error: function (xhr) {
                var msg = 'API error — page refresh karo ya dubara login karo.';
                if (xhr.responseText) {
                    try {
                        var j = JSON.parse(xhr.responseText);
                        if (j.message) msg = j.message;
                    } catch (e) {
                        msg = xhr.responseText.substring(0, 120);
                    }
                }
                setStatus(esc(msg), 'bad');
            }
        });
    }

    function tableHtml(cols, rows) {
        if (!rows || !rows.length) {
            return '<div class="dbc-exec-ok">0 records.</div>';
        }
        var h = '<div class="dbc-table-wrap"><table><thead><tr>';
        $.each(cols, function (_, c) { h += '<th>' + esc(c) + '</th>'; });
        h += '</tr></thead><tbody>';
        $.each(rows, function (_, row) {
            h += '<tr>';
            $.each(cols, function (_, c) { h += '<td>' + esc(row[c]) + '</td>'; });
            h += '</tr>';
        });
        return h + '</tbody></table></div>';
    }

    function appendUser(text) {
        $('#dbc-empty').remove();
        $('#dbc-chat').append(
            '<div class="dbc-msg dbc-msg-user">' +
            '<div class="dbc-label">You</div>' +
            '<div class="dbc-bubble">' + esc(text) + '</div></div>'
        );
        scrollChat();
    }

    function appendAi(reply, execResults) {
        var html = '<div class="dbc-msg dbc-msg-ai"><div class="dbc-label">AI</div><div class="dbc-bubble">';
        html += esc(reply.replace(/```[\s\S]*?```/g, '').trim() || reply);

        if (execResults && execResults.length) {
            $.each(execResults, function (_, ex) {
                html += '<div class="dbc-sql">' + esc(ex.sql) + '</div>';
                if (ex.error) {
                    html += '<div class="dbc-exec-err">✗ ' + esc(ex.error) + '</div>';
                } else if (ex.executed) {
                    html += '<div class="dbc-exec-ok">✓ ' + esc(ex.message || 'Done') + '</div>';
                    if (ex.rows && ex.columns) {
                        html += tableHtml(ex.columns, ex.rows);
                    }
                } else if (ex.message) {
                    html += '<div style="color:#888;font-size:13px;margin-top:6px;">' + esc(ex.message) + '</div>';
                }
            });
        }

        html += '</div></div>';
        $('#dbc-chat').append(html);
        scrollChat();
    }

    function scrollChat() {
        var el = document.getElementById('dbc-chat');
        el.scrollTop = el.scrollHeight;
    }

    function showThinking() {
        $('#dbc-chat').append('<div class="dbc-thinking" id="dbc-thinking">AI soch raha hai…</div>');
        scrollChat();
    }

    function hideThinking() {
        $('#dbc-thinking').remove();
    }

    function submit() {
        if (busy) return;
        var msg = $.trim($('#dbc-input').val());
        if (!msg) return;

        busy = true;
        $('#dbc-submit').prop('disabled', true);
        appendUser(msg);
        $('#dbc-input').val('');
        showThinking();

        $.ajax({
            url: api,
            method: 'POST',
            data: {
                ai_chat: 1,
                message: msg,
                history: JSON.stringify(history)
            },
            dataType: 'json',
            xhrFields: { withCredentials: true },
            success: function (r) {
            busy = false;
            $('#dbc-submit').prop('disabled', false);
            hideThinking();

            if (!r.success) {
                appendAi('Error: ' + r.message, []);
                return;
            }

            appendAi(r.reply, r.exec_results || []);

            history.push({ role: 'user', content: msg });
            history.push({ role: 'assistant', content: r.reply });
            if (history.length > 24) history = history.slice(-24);
            },
            error: function () {
                busy = false;
                $('#dbc-submit').prop('disabled', false);
                hideThinking();
                appendAi('Request failed. Page refresh karo ya dubara login karo.', []);
            }
        });
    }

    $('#dbc-submit').on('click', submit);
    $('#dbc-input').on('keydown', function (e) {
        if (e.ctrlKey && e.key === 'Enter') submit();
    });

    ping();
})();
</script>
</body>
</html>
