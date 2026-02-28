{{--
 * FileHost Addon for ClientXCMS V2
 * Author: Corentin WebSite
 * Year: 2026
 * License: Open Source
 *
 * Disclaimer: La maintenance de fonctionnement est assurée par Corentin WebSite.
 * En cas de modification du code par un tiers, l'auteur décline toute responsabilité
 * si le logiciel ne fonctionne plus correctement.
 --}}
@extends('admin.layouts.admin')

@section('title', __('file-host::messages.title'))

@section('content')
<div class="container mx-auto">

    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">


                <div class="card">


                    <div class="card-heading">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                <i class="bi bi-hdd mr-2"></i>{{ __('file-host::messages.title') }}
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('file-host::messages.subtitle') }}
                            </p>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                            {{ $files->total() ?? 0 }} {{ __('file-host::messages.file') }}(s)
                        </div>
                    </div>


                    @include('admin.shared.alerts')


                    <div class="p-4 border-b dark:border-gray-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div class="border rounded-lg dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 border-b dark:border-gray-700 bg-gray-50 dark:bg-slate-800 flex items-center gap-2">
                                    <i class="bi bi-cloud-arrow-up text-gray-600 dark:text-gray-400"></i>
                                    <span class="font-semibold text-sm text-gray-800 dark:text-gray-200">{{ __('file-host::messages.upload_title') }}</span>
                                </div>
                                <div class="p-4">
                                    <form action="{{ route('admin.file-host.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                                        @csrf
                                        <input type="file" name="file" id="fileInput" style="display:none;" onchange="onFileChosen(this)" required>

                                        <div id="dropZone"
                                             onclick="document.getElementById('fileInput').click()"
                                             ondragover="event.preventDefault();this.classList.add('dz-over')"
                                             ondragleave="this.classList.remove('dz-over')"
                                             ondrop="handleFileDrop(event)"
                                             class="border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer transition-all hover:border-gray-400 dark:hover:border-gray-400 hover:bg-gray-50 dark:hover:bg-slate-800">

                                            <div id="dz-icon" class="text-4xl text-gray-300 dark:text-gray-600 mb-2 transition-transform">
                                                <i class="bi bi-cloud-arrow-up-fill"></i>
                                            </div>
                                            <div id="dz-title" class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('file-host::messages.drop_files') }}</div>
                                            <div id="dz-sub" class="text-xs text-gray-400 mt-1">
                                                {{ __('file-host::messages.click_to_choose') }} — {{ __('file-host::messages.max_size') }}
                                            </div>
                                            <div id="dz-file-info" style="display:none" class="mt-3">
                                                <div class="inline-flex items-center gap-2 bg-gray-100 dark:bg-slate-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-1.5">
                                                    <i class="bi bi-file-earmark-check text-green-500"></i>
                                                    <span id="dz-filename" class="text-xs font-bold text-gray-700 dark:text-gray-300 max-w-[180px] truncate block"></span>
                                                    <span id="dz-filesize" class="text-xs text-gray-400"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="dz-actions" style="display:none" class="mt-3 flex justify-end items-center gap-2">
                                            <button type="button" onclick="resetUpload()" class="btn btn-light text-sm">{{ __('file-host::messages.cancel') }}</button>
                                            <button type="submit" class="btn btn-primary text-sm">
                                                <i class="bi bi-upload mr-1"></i> {{ __('file-host::messages.submit_upload') }}
                                            </button>
                                        </div>
                                        <div id="dz-footer" class="mt-2 text-right">
                                            <span class="text-xs text-gray-300 dark:text-gray-600">
                                                <i class="bi bi-lock mr-1"></i>{{ __('file-host::messages.private_hosting') }}
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="border rounded-lg dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 border-b dark:border-gray-700 bg-gray-50 dark:bg-slate-800 flex items-center gap-2">
                                    <i class="bi bi-gear text-gray-600 dark:text-gray-400"></i>
                                    <span class="font-semibold text-sm text-gray-800 dark:text-gray-200">{{ __('file-host::messages.url_config') }}</span>
                                </div>
                                <div class="p-4">
                                    <form action="{{ route('admin.file-host.settings.update') }}" method="POST">
                                        @csrf
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('file-host::messages.url_prefix') }}</label>
                                        <div class="flex rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden mb-3">
                                            <span class="flex items-center px-3 bg-gray-50 dark:bg-slate-800 text-gray-400 text-sm border-r border-gray-200 dark:border-gray-600 whitespace-nowrap">
                                                {{ rtrim(config('app.url'), '/') }}/
                                            </span>
                                            <input type="text" name="file_host_prefix" value="{{ $prefix ?? 'drive' }}"
                                                   class="input-text rounded-none border-0 flex-1 text-sm" placeholder="drive" required>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-400"><i class="bi bi-link-45deg"></i> /{{ $prefix ?? 'drive' }}/fichier.png</span>
                                            <button type="submit" class="btn btn-primary text-sm">
                                                <i class="bi bi-check-lg mr-1"></i> {{ __('file-host::messages.save') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="border rounded-lg overflow-hidden dark:border-gray-700 m-4">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">{{ __('file-host::messages.file') }}</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">{{ __('file-host::messages.link') }}</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">{{ __('file-host::messages.size') }}</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">{{ __('file-host::messages.views') }}</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">{{ __('file-host::messages.date') }}</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">{{ __('file-host::messages.actions') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($files as $file)
                                @php
                                    $isPreviewable = str_starts_with($file->mime_type, 'image/')
                                        || $file->mime_type === 'application/pdf'
                                        || str_starts_with($file->mime_type, 'video/');
                                    $previewType = str_starts_with($file->mime_type, 'image/') ? 'image'
                                        : ($file->mime_type === 'application/pdf' ? 'pdf'
                                        : (str_starts_with($file->mime_type, 'video/') ? 'video' : ''));
                                @endphp
                                <tr class="bg-white hover:bg-gray-50 dark:bg-slate-900 dark:hover:bg-slate-800">

                                    {{-- Fichier --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 flex items-center justify-center bg-gray-50 dark:bg-slate-800 text-xl flex-shrink-0 {{ $isPreviewable ? 'cursor-pointer' : '' }}"
                                                 @if($isPreviewable) onclick="openPreview('{{ $file->url }}', {{ \Illuminate\Support\Js::from($file->original_name) }}, '{{ $previewType }}')" title="{{ __('file-host::messages.preview') }}" @endif>
                                                @if(str_starts_with($file->mime_type, 'image/'))
                                                    <img src="{{ $file->url }}" alt="{{ $file->original_name }}" class="w-full h-full object-cover">
                                                @elseif($file->mime_type === 'application/pdf')
                                                    <i class="bi bi-file-earmark-pdf text-red-500"></i>
                                                @elseif(str_starts_with($file->mime_type, 'video/'))
                                                    <i class="bi bi-file-earmark-play text-orange-400"></i>
                                                @elseif(str_starts_with($file->mime_type, 'audio/'))
                                                    <i class="bi bi-file-earmark-music text-gray-400"></i>
                                                @elseif(str_contains($file->mime_type, 'zip') || str_contains($file->mime_type, 'rar'))
                                                    <i class="bi bi-file-earmark-zip text-gray-400"></i>
                                                @else
                                                    <i class="bi bi-file-earmark text-gray-400"></i>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                @if($isPreviewable)
                                                    <div class="flex items-center gap-1 cursor-pointer group"
                                                         onclick="openPreview('{{ $file->url }}', {{ \Illuminate\Support\Js::from($file->original_name) }}, '{{ $previewType }}')"
                                                         title="{{ __('file-host::messages.click_preview') }}">
                                                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate max-w-[180px] group-hover:underline">{{ $file->original_name }}</span>
                                                        <i class="bi bi-eye text-gray-400 text-xs flex-shrink-0"></i>
                                                    </div>
                                                @else
                                                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate max-w-[200px] block">{{ $file->original_name }}</span>
                                                @endif
                                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase">
                                                    {{ explode('/', $file->mime_type ?? '')[1] ?? '—' }}
                                                    @if($isPreviewable)<span class="normal-case text-gray-300 dark:text-gray-600">· {{ __('file-host::messages.preview_available') }}</span>@endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Lien --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <code class="text-xs bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 px-2 py-1 rounded block max-w-[160px] truncate" title="{{ $file->url }}">
                                            /{{ $prefix ?? 'drive' }}/{{ \Illuminate\Support\Str::limit($file->uuid, 20) }}
                                        </code>
                                    </td>

                                    {{-- Taille --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $file->human_size }}
                                        </span>
                                    </td>

                                    {{-- Vues --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            <i class="bi bi-eye mr-1"></i>{{ $file->views }}
                                        </span>
                                    </td>

                                    {{-- Date --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $file->created_at ? $file->created_at->format('d/m/Y H:i') : '—' }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            @if($isPreviewable)
                                            <button type="button" title="{{ __('file-host::messages.preview') }}"
                                                onclick="openPreview('{{ $file->url }}', {{ \Illuminate\Support\Js::from($file->original_name) }}, '{{ $previewType }}')"
                                                class="btn-icon"><i class="bi bi-eye"></i></button>
                                            @endif
                                            <button type="button" title="{{ __('file-host::messages.edit') }}"
                                                onclick="openEditModal({{ $file->id }}, {{ \Illuminate\Support\Js::from($file->original_name) }}, {{ \Illuminate\Support\Js::from($file->uuid) }})"
                                                class="btn-icon"><i class="bi bi-pencil"></i></button>
                                            <button type="button" title="{{ __('file-host::messages.copy_link') }}"
                                                onclick="copyLink('{{ $file->url }}')"
                                                class="btn-icon"><i class="bi bi-clipboard"></i></button>
                                            <a href="{{ $file->url }}" target="_blank" title="{{ __('file-host::messages.open') }}" class="btn-icon">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                            <form action="{{ route('admin.file-host.destroy', $file->id) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('{{ __('file-host::messages.confirm_delete') ?? 'Supprimer ?' }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="{{ __('file-host::messages.delete') }}" class="btn-icon text-red-500 hover:text-red-700">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-white dark:bg-slate-900">
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="bi bi-cloud-upload text-4xl text-gray-300 dark:text-gray-600 mb-2"></i>
                                            <p class="text-gray-500 dark:text-gray-400">{{ __('file-host::messages.no_files') }}</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if(method_exists($files, 'links') && $files->hasPages())
                    <div class="py-1 px-4 mx-auto">
                        {!! $files->appends(request()->query())->links('pagination::tailwind') !!}
                    </div>
                    @endif

                </div>{{-- /card --}}
            </div>
        </div>
    </div>
</div>


<div id="copy-toast" class="fixed bottom-6 right-6 bg-slate-800 text-slate-50 px-5 py-3 rounded-xl text-sm font-bold flex items-center gap-2 shadow-2xl translate-y-20 opacity-0 transition-all z-[9999]">
    <i class="bi bi-check-circle-fill text-green-500"></i> {{ __('file-host::messages.link_copied') }}
</div>


<div id="previewBackdrop" onclick="if(event.target===this)closePreview()"
     class="hidden fixed inset-0 bg-black/90 z-[2000] flex-col items-center justify-center p-4">
    <div class="flex items-center justify-between w-full max-w-5xl mb-4">
        <div>
            <div id="preview-name" class="text-lg font-extrabold text-slate-50"></div>
            <div id="preview-meta" class="text-xs text-slate-400 mt-1 uppercase tracking-wider"></div>
        </div>
        <div class="flex items-center gap-3">
            <a id="preview-open-link" href="#" target="_blank"
               class="inline-flex items-center gap-2 bg-white/10 text-slate-100 border border-white/10 hover:bg-white/20 px-4 py-2 rounded-xl text-sm font-semibold transition-colors">
                <i class="bi bi-box-arrow-up-right"></i> {{ __('file-host::messages.open') ?? 'Ouvrir' }}
            </a>
            <button onclick="closePreview()"
                    class="bg-white/10 hover:bg-white/20 border border-white/10 text-slate-50 w-10 h-10 rounded-xl flex items-center justify-center transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>
    <div id="preview-content"
         class="w-full max-w-5xl max-h-[80vh] flex items-center justify-center overflow-hidden rounded-2xl bg-white/5 border border-white/10 shadow-2xl">
    </div>
</div>

{{-- Edit Modal --}}
<div id="editBackdrop" onclick="if(event.target===this)closeEditModal()"
     class="hidden fixed inset-0 bg-black/60 z-[1000] flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl mx-4">
        <div class="px-6 py-4 bg-slate-800 flex items-center gap-3">
            <i class="bi bi-pencil-square text-white text-lg"></i>
            <span class="font-bold text-white text-base">{{ __('file-host::messages.edit_file') }}</span>
        </div>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="p-6 flex flex-col gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">{{ __('file-host::messages.file_name') }}</label>
                    <input type="text" name="original_name" id="edit_original_name" class="input-text" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">{{ __('file-host::messages.link') }}</label>
                    <div class="flex border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500 transition-all">
                        <span class="flex items-center px-4 bg-slate-50 dark:bg-slate-800 text-slate-400 font-bold text-sm border-r border-slate-200 dark:border-slate-700 whitespace-nowrap">
                            /{{ $prefix ?? 'drive' }}/
                        </span>
                        <input type="text" name="uuid" id="edit_uuid"
                               class="flex-1 px-4 py-2.5 outline-none text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-w-0" required>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeEditModal()" class="btn btn-light shadow-sm">{{ __('file-host::messages.cancel') }}</button>
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="bi bi-check-lg mr-1"></i> {{ __('file-host::messages.save_changes') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        const t = document.getElementById('copy-toast');
        t.style.transform = 'translateY(0)'; t.style.opacity = '1';
        setTimeout(() => { t.style.transform = 'translateY(5rem)'; t.style.opacity = '0'; }, 2500);
    });
}
function openPreview(url, name, type) {
    const escapeHtml = (str) => str.replace(/[&<>"']/g, m => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[m]));
    document.getElementById('preview-name').textContent = name;
    document.getElementById('preview-meta').textContent = type.toUpperCase();
    document.getElementById('preview-open-link').href = url;
    const c = document.getElementById('preview-content');
    c.innerHTML = ''; c.style.height = '';
    const safeName = escapeHtml(name);
    if (type === 'image') {
        c.innerHTML = `<img src="${url}" class="max-w-full max-h-[80vh] object-contain rounded-xl block" alt="${safeName}">`;
    } else if (type === 'pdf') {
        c.classList.add('h-[80vh]');
        c.innerHTML = `<iframe src="${url}" class="w-full h-full border-none rounded-xl"></iframe>`;
    } else if (type === 'video') {
        c.innerHTML = `<video controls autoplay class="max-w-full max-h-[80vh] rounded-xl"><source src="${url}">Non supporté.</video>`;
    }
    const b = document.getElementById('previewBackdrop');
    b.classList.remove('hidden');
    b.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closePreview() {
    const b = document.getElementById('previewBackdrop');
    b.classList.add('hidden');
    b.classList.remove('flex');
    document.getElementById('preview-content').innerHTML = '';
    document.body.style.overflow = '';
}
function openEditModal(id, name, uuid) {
    document.getElementById('editForm').action = `{{ url(admin_prefix() . '/file-host') }}/${id}`;
    document.getElementById('edit_original_name').value = name;
    document.getElementById('edit_uuid').value = uuid;
    const b = document.getElementById('editBackdrop');
    b.classList.remove('hidden');
    b.classList.add('flex');
}
function closeEditModal() { 
    const b = document.getElementById('editBackdrop');
    b.classList.add('hidden');
    b.classList.remove('flex');
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') { closePreview(); closeEditModal(); } });

/* Dropzone */
function onFileChosen(i) { if (i.files && i.files[0]) showFileInfo(i.files[0]); }
function handleFileDrop(e) {
    e.preventDefault();
    document.getElementById('dropZone').classList.remove('dz-over');
    const f = e.dataTransfer.files;
    if (f && f[0]) { const i = document.getElementById('fileInput'); const d = new DataTransfer(); d.items.add(f[0]); i.files = d.files; showFileInfo(f[0]); }
}
function showFileInfo(f) {
    document.getElementById('dz-filename').textContent = f.name;
    const kb = f.size / 1024;
    document.getElementById('dz-filesize').textContent = kb >= 1024 ? '(' + (kb/1024).toFixed(2) + ' Mo)' : '(' + Math.round(kb) + ' Ko)';
    document.getElementById('dz-file-info').style.display = 'block';
    document.getElementById('dz-actions').style.display = 'flex';
    document.getElementById('dz-footer').style.display = 'none';
    document.getElementById('dz-title').textContent = "✓";
    document.getElementById('dz-sub').textContent = "";
    document.getElementById('dz-icon').innerHTML = '<i class="bi bi-check-circle-fill" style="color:#22c55e;font-size:2.5rem;"></i>';
}
function resetUpload() {
    document.getElementById('fileInput').value = '';
    document.getElementById('dz-file-info').style.display = 'none';
    document.getElementById('dz-actions').style.display = 'none';
    document.getElementById('dz-footer').style.display = 'block';
    document.getElementById('dz-title').textContent = "{{ __('file-host::messages.drop_files') }}";
    document.getElementById('dz-sub').innerHTML = "{{ __('file-host::messages.click_to_choose') }} — {{ __('file-host::messages.max_size') }}";
    document.getElementById('dz-icon').innerHTML = '<i class="bi bi-cloud-arrow-up-fill"></i>';
}
</script>
@endsection
