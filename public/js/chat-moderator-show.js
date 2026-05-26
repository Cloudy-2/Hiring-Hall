(function () {
  function ready(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
      return;
    }
    fn();
  }

  ready(function () {
    var root = document.getElementById('chat-moderator-page');
    if (!root) return;

    var conversationId = root.dataset.conversationId;
    var conversationTitle = root.dataset.conversationTitle || 'this conversation';
    var csrfToken = root.dataset.csrfToken;
    var selectedMembers = [];

    function hasSwal() {
      return typeof window.Swal !== 'undefined' && typeof window.Swal.fire === 'function';
    }

    async function confirmAction(opts) {
      if (hasSwal()) {
        return window.Swal.fire(opts);
      }
      var text = (opts && (opts.text || opts.title)) || 'Are you sure?';
      return { isConfirmed: window.confirm(text) };
    }

    function showAlert(type, title, text) {
      if (hasSwal()) {
        return window.Swal.fire({ icon: type, title: title, text: text });
      }
      window.alert((title ? title + ': ' : '') + (text || ''));
      return Promise.resolve();
    }

    function showLoading(title) {
      if (hasSwal()) {
        window.Swal.fire({ title: title || 'Please wait...', allowOutsideClick: false, didOpen: function () { window.Swal.showLoading(); } });
      }
    }

    async function parseResponseSafely(res) {
      var contentType = res.headers.get('content-type') || '';
      if (contentType.indexOf('application/json') !== -1) {
        return res.json();
      }
      var text = await res.text();
      return { message: text || ('Request failed with status ' + res.status) };
    }

    function updateSelectedMembersUI() {
      var container = document.getElementById('selected-members');
      var countEl = document.getElementById('selected-count');
      if (!container || !countEl) return;

      countEl.textContent = '(' + selectedMembers.length + ')';
      if (selectedMembers.length === 0) {
        container.innerHTML = '<span class="text-xs text-textmuted">No members selected yet</span>';
        return;
      }

      container.innerHTML = selectedMembers.map(function (m) {
        return '<span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary rounded-full text-xs">' +
          m.name +
          '<button type="button" onclick="removeMember(' + m.id + ')" class="hover:text-danger"><i class="bi bi-x"></i></button>' +
          '</span>';
      }).join('');
    }

    function closeAddMemberModal() {
      var modal = document.getElementById('add-member-modal');
      var input = document.getElementById('member-search');
      var results = document.getElementById('member-search-results');
      if (!modal) return;
      modal.classList.add('hidden');
      if (input) input.value = '';
      selectedMembers = [];
      updateSelectedMembersUI();
      if (results) {
        results.innerHTML = '<div class="p-4 text-center text-textmuted text-sm">Start typing to search for users</div>';
      }
    }

    function selectMember(id, name, email) {
      for (var i = 0; i < selectedMembers.length; i += 1) {
        if (selectedMembers[i].id === id) return;
      }
      selectedMembers.push({ id: id, name: name, email: email });
      updateSelectedMembersUI();
    }

    function removeMember(id) {
      selectedMembers = selectedMembers.filter(function (m) { return m.id !== id; });
      updateSelectedMembersUI();
    }

    function escapeHtml(s) {
      return (s || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    function searchMembers(query) {
      var resultsContainer = document.getElementById('member-search-results');
      if (!resultsContainer) return;

      if (!query || query.length < 2) {
        resultsContainer.innerHTML = '<div class="p-4 text-center text-textmuted text-sm">Type at least 2 characters to search</div>';
        return;
      }

      resultsContainer.innerHTML = '<div class="p-4 text-center text-textmuted text-sm">Searching...</div>';
      var params = new URLSearchParams({ conversation_id: conversationId, q: query });

      fetch('/chats/manage/users-by-role?' + params.toString(), {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
      })
        .then(function (response) { return response.json(); })
        .then(function (users) {
          if (!Array.isArray(users) || users.length === 0) {
            resultsContainer.innerHTML = '<div class="p-4 text-center text-textmuted text-sm">No users found</div>';
            return;
          }

          resultsContainer.innerHTML = users.map(function (user) {
            var name = escapeHtml(user.name || '');
            var email = escapeHtml(user.email || '');
            var avatar = escapeHtml(user.avatar || '');
            var initials = escapeHtml((user.name || '').substring(0, 2).toUpperCase() || '?');
            return '' +
              '<div class="member-result flex items-center gap-3 p-3 hover:bg-light/50 cursor-pointer border-b dark:border-defaultborder/10 last:border-0" data-user-id="' + user.id + '" data-user-name="' + name + '" data-user-email="' + email + '">' +
              '<img src="' + avatar + '" alt="" class="w-8 h-8 rounded-full object-cover shrink-0" data-initials="' + initials + '" onerror="this.outerHTML=\'<span class=\\\'inline-flex w-8 h-8 rounded-full bg-primary/10 items-center justify-center text-xs font-semibold text-primary shrink-0\\\'>\'+this.dataset.initials+\'</span>\'">' +
              '<div class="flex-1 min-w-0">' +
              '<p class="text-sm font-medium truncate">' + name + '</p>' +
              '<p class="text-xs text-textmuted truncate">' + email + '</p>' +
              '</div>' +
              '<i class="bi bi-plus-circle text-primary"></i>' +
              '</div>';
          }).join('');

          resultsContainer.querySelectorAll('.member-result').forEach(function (row) {
            row.addEventListener('click', function () {
              var userId = Number(row.getAttribute('data-user-id') || 0);
              var userName = row.getAttribute('data-user-name') || '';
              var userEmail = row.getAttribute('data-user-email') || '';
              if (!userId) return;
              selectMember(userId, userName, userEmail);
            });
          });
        })
        .catch(function () {
          resultsContainer.innerHTML = '<div class="p-4 text-center text-danger text-sm">Error searching users</div>';
        });
    }

    async function submitAddMembers() {
      if (selectedMembers.length === 0) {
        await showAlert('warning', 'No members selected', 'Please select at least one member to add.');
        return;
      }

      showLoading('Adding members...');
      try {
        var res = await fetch('/chats/manage/' + conversationId + '/add-member', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ user_ids: selectedMembers.map(function (m) { return m.id; }) })
        });
        var data = await parseResponseSafely(res);
        if (res.ok) {
          await showAlert('success', 'Members added', data.message || 'Members added successfully.');
          closeAddMemberModal();
          window.location.reload();
          return;
        }
        await showAlert('error', 'Error', data.message || 'Failed to add members');
      } catch (e) {
        await showAlert('error', 'Error', e && e.message ? e.message : 'Request failed');
      }
    }

    async function postAction(url, successTitle) {
      try {
        var res = await fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          }
        });
        var data = await parseResponseSafely(res);
        if (res.ok) {
          await showAlert('success', successTitle, data.message || 'Completed successfully');
          return true;
        }
        await showAlert('error', 'Error', data.message || 'Failed');
        return false;
      } catch (e) {
        await showAlert('error', 'Error', e && e.message ? e.message : 'Request failed');
        return false;
      }
    }

    var addMemberBtn = document.getElementById('btn-add-member');
    if (addMemberBtn) {
      addMemberBtn.addEventListener('click', function () {
        var modal = document.getElementById('add-member-modal');
        if (modal) modal.classList.remove('hidden');
      });
    }

    var muteChatBtn = document.getElementById('btn-mute-chat');
    if (muteChatBtn) {
      muteChatBtn.addEventListener('click', async function () {
        var result = await confirmAction({ title: 'Mute notifications?', text: 'You will stop receiving notifications for "' + conversationTitle + '".' });
        if (!result.isConfirmed) return;
        await postAction('/chats/manage/' + conversationId + '/mute-self', 'Muted');
      });
    }

    var unmuteChatBtn = document.getElementById('btn-unmute-chat');
    if (unmuteChatBtn) {
      unmuteChatBtn.addEventListener('click', async function () {
        var result = await confirmAction({ title: 'Unmute notifications?', text: 'You will start receiving notifications for "' + conversationTitle + '" again.' });
        if (!result.isConfirmed) return;
        await postAction('/chats/manage/' + conversationId + '/unmute-self', 'Unmuted');
      });
    }

    var lockChatBtn = document.getElementById('btn-lock-chat');
    if (lockChatBtn) {
      lockChatBtn.addEventListener('click', async function () {
        var result = await confirmAction({ title: 'Lock this conversation?', text: 'Members will not be able to send messages in "' + conversationTitle + '".' });
        if (!result.isConfirmed) return;
        var ok = await postAction('/chats/manage/' + conversationId + '/lock', 'Locked');
        if (ok) window.location.reload();
      });
    }

    var unlockChatBtn = document.getElementById('btn-unlock-chat');
    if (unlockChatBtn) {
      unlockChatBtn.addEventListener('click', async function () {
        var result = await confirmAction({ title: 'Unlock this conversation?', text: 'Members will be able to send messages again in "' + conversationTitle + '".' });
        if (!result.isConfirmed) return;
        var ok = await postAction('/chats/manage/' + conversationId + '/unlock', 'Unlocked');
        if (ok) window.location.reload();
      });
    }

    document.querySelectorAll('.btn-mute-member').forEach(function (btn) {
      btn.addEventListener('click', async function () {
        var memberId = this.dataset.memberId;
        var memberName = this.dataset.memberName || 'this member';
        var result = await confirmAction({ title: 'Mute this member?', text: memberName + ' will not be able to send messages.' });
        if (!result.isConfirmed) return;
        try {
          var res = await fetch('/chats/manage/' + conversationId + '/members/' + memberId + '/mute', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ duration: 60 })
          });
          var data = await parseResponseSafely(res);
          if (res.ok) {
            await showAlert('success', 'Muted', data.message || 'Member muted.');
            window.location.reload();
            return;
          }
          await showAlert('error', 'Error', data.message || 'Failed');
        } catch (e) {
          await showAlert('error', 'Error', e && e.message ? e.message : 'Request failed');
        }
      });
    });

    document.querySelectorAll('.btn-unmute-member').forEach(function (btn) {
      btn.addEventListener('click', async function () {
        var memberId = this.dataset.memberId;
        var memberName = this.dataset.memberName || 'this member';
        var result = await confirmAction({ title: 'Unmute this member?', text: memberName + ' will be allowed to send messages again.' });
        if (!result.isConfirmed) return;
        var ok = await postAction('/chats/manage/' + conversationId + '/members/' + memberId + '/unmute', 'Unmuted');
        if (ok) window.location.reload();
      });
    });

    document.querySelectorAll('.btn-remove-member').forEach(function (btn) {
      btn.addEventListener('click', async function () {
        var memberId = this.dataset.memberId;
        var memberName = this.dataset.memberName || 'this member';
        var result = await confirmAction({ title: 'Remove this member?', text: memberName + ' will be removed from this conversation.' });
        if (!result.isConfirmed) return;
        var ok = await postAction('/chats/manage/' + conversationId + '/members/' + memberId + '/kick', 'Removed');
        if (ok) window.location.reload();
      });
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeAddMemberModal();
    });

    window.closeAddMemberModal = closeAddMemberModal;
    window.searchMembers = searchMembers;
    window.selectMember = selectMember;
    window.removeMember = removeMember;
    window.submitAddMembers = submitAddMembers;
  });
})();
