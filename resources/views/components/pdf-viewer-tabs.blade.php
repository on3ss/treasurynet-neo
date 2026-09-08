{{--
    Document collection viewer.

    Renders a set of document collections (each possibly containing several
    files) as Filament-native tabs, with each file displayed in an embedded
    PDF viewer (@embedpdf/snippet) whose accent colors are synced to the
    current Filament panel theme.

    Expects a `$documentCollections` variable in scope, shaped like:
    [
        [
            'label' => 'Contracts',
            'files' => [
                ['label' => 'Master Agreement', 'url' => '...'],
                ['label' => 'Amendment 1', 'url' => '...'],
            ],
        ],
        ...
    ]
--}}
<div
    x-data="documentViewer(@js($documentCollections))"
    class="w-full"
>
    {{-- Collection-level tabs, rendered server-side so we get Filament's real tab styling for free --}}
    <x-filament::tabs label="Document collections" style="margin-bottom: 1rem;">
        @foreach ($documentCollections as $collectionIndex => $collection)
            <x-filament::tabs.item
                :alpine-active="'activeCollection === ' . $collectionIndex"
                x-on:click="setActiveCollection({{ $collectionIndex }})"
            >
                {{ $collection['label'] }}
            </x-filament::tabs.item>
        @endforeach
    </x-filament::tabs>

    @foreach ($documentCollections as $collectionIndex => $collection)
        <div x-show="activeCollection === {{ $collectionIndex }}" class="w-full">

            {{-- File-level tabs, only shown when a collection has more than one file --}}
            @if (count($collection['files']) > 1)
                <x-filament::tabs label="{{ $collection['label'] }} files" style="margin-bottom: 0.75rem;">
                    @foreach ($collection['files'] as $fileIndex => $file)
                        <x-filament::tabs.item
                            :alpine-active="'activeFiles[' . $collectionIndex . '] === ' . $fileIndex"
                            x-on:click="setActiveFile({{ $collectionIndex }}, {{ $fileIndex }})"
                        >
                            {{ $file['label'] }}
                        </x-filament::tabs.item>
                    @endforeach
                </x-filament::tabs>
            @endif

            @foreach ($collection['files'] as $fileIndex => $file)
                <div x-show="activeFiles[{{ $collectionIndex }}] === {{ $fileIndex }}" class="w-full">
                    {{-- Inline styles here (not Tailwind classes) are deliberate: this
                         partial may not be inside your Tailwind `content` scan paths,
                         and if `h-full`/`w-full`/etc. never get compiled, the viewer
                         container silently collapses to zero height. Inline styles
                         always apply regardless of the build. --}}
                    <div style="position: relative; height: 80vh;">

                        {{-- Loading state, reusing Filament's own loading indicator --}}
                        <div
                            x-show="isLoading({{ $collectionIndex }}, {{ $fileIndex }})"
                            style="position: absolute; inset: 0; z-index: 10;"
                            class="flex items-center justify-center rounded-lg bg-white dark:bg-gray-900"
                        >
                            <x-filament::loading-indicator class="h-8 w-8 text-primary-600" />
                        </div>

                        {{-- Error state, reusing Filament's icon component --}}
                        <div
                            x-show="hasError({{ $collectionIndex }}, {{ $fileIndex }})"
                            x-cloak
                            style="position: absolute; inset: 0; z-index: 10;"
                            class="flex flex-col items-center justify-center gap-2 rounded-lg bg-white text-center dark:bg-gray-900"
                        >
                            <x-filament::icon
                                icon="heroicon-o-exclamation-triangle"
                                class="h-8 w-8 text-danger-500"
                            />
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('This document could not be loaded.') }}
                            </p>
                        </div>

                        <div
                            :id="`pdf-viewer-{{ $collectionIndex }}-{{ $fileIndex }}`"
                            style="height: 100%; width: 100%; overflow: hidden; border-radius: 0.5rem;"
                        ></div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>

<script>
    function documentViewer(documentCollections) {
        return {
            documentCollections,
            activeCollection: 0,
            activeFiles: {},
            theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
            viewers: {},
            status: {}, // `${collectionIndex}-${fileIndex}` -> 'loading' | 'ready' | 'error'

            init() {
                this.documentCollections.forEach((_, index) => {
                    this.activeFiles[index] = 0;
                });

                // Filament fires this event on the `dark-mode-toggled.window` listener
                // whenever the user (or system preference) switches theme.
                window.addEventListener('dark-mode-toggled', (event) => {
                    this.theme = event.detail ? 'dark' : 'light';
                    Object.values(this.viewers).forEach((viewer) => {
                        viewer?.updateTheme?.(this.buildEmbedTheme(this.theme));
                    });
                });

                this.$nextTick(() => this.initViewer(this.activeCollection, this.activeFiles[this.activeCollection]));
            },

            setActiveCollection(index) {
                this.activeCollection = index;
                this.$nextTick(() => this.initViewer(index, this.activeFiles[index] ?? 0));
            },

            setActiveFile(collectionIndex, fileIndex) {
                this.activeFiles[collectionIndex] = fileIndex;
                if (collectionIndex === this.activeCollection) {
                    this.$nextTick(() => this.initViewer(collectionIndex, fileIndex));
                }
            },

            isLoading(collectionIndex, fileIndex) {
                return this.status[`${collectionIndex}-${fileIndex}`] !== 'ready'
                    && this.status[`${collectionIndex}-${fileIndex}`] !== 'error';
            },

            hasError(collectionIndex, fileIndex) {
                return this.status[`${collectionIndex}-${fileIndex}`] === 'error';
            },

            /**
             * Reads Filament's own CSS custom properties for the panel's
             * configured primary/gray palette, so the embedded PDF viewer's
             * chrome matches the panel theme instead of a hardcoded color.
             * Falls back to Filament's default indigo/gray if the
             * variables aren't present (e.g. component used outside a panel).
             */
            buildEmbedTheme(mode) {
                const styles = getComputedStyle(document.documentElement);

                const triplet = (name, fallback) => {
                    const value = styles.getPropertyValue(name).trim();
                    return value ? value : fallback;
                };

                const toHex = (rgbTriplet) => {
                    const [r, g, b] = rgbTriplet.split(/\s+/).map(Number);
                    return '#' + [r, g, b].map((n) => n.toString(16).padStart(2, '0')).join('');
                };

                const primary = {
                    500: toHex(triplet('--primary-500', '99 102 241')),
                    600: toHex(triplet('--primary-600', '79 70 229')),
                    700: toHex(triplet('--primary-700', '67 56 202')),
                    100: toHex(triplet('--primary-100', '224 231 255')),
                };

                const gray = {
                    50: toHex(triplet('--gray-50', '249 250 251')),
                    100: toHex(triplet('--gray-100', '243 244 246')),
                    200: toHex(triplet('--gray-200', '229 231 235')),
                    400: toHex(triplet('--gray-400', '156 163 175')),
                    600: toHex(triplet('--gray-600', '107 114 128')),
                    800: toHex(triplet('--gray-800', '31 41 55')),
                    900: toHex(triplet('--gray-900', '17 24 39')),
                };

                const shared = {
                    accent: {
                        primary: primary[600],
                        primaryHover: primary[700],
                        primaryActive: primary[700],
                        primaryLight: primary[100],
                        primaryForeground: '#ffffff',
                    },
                };

                return {
                    preference: mode,
                    light: {
                        ...shared,
                        surface: {
                            background: '#ffffff',
                            surface: gray[50],
                            surfaceHover: gray[100],
                            border: gray[200],
                            text: gray[900],
                            textSecondary: gray[600],
                        },
                    },
                    dark: {
                        ...shared,
                        surface: {
                            background: gray[900],
                            surface: gray[800],
                            surfaceHover: gray[600],
                            border: gray[600],
                            text: gray[100] ?? '#e5e7eb',
                            textSecondary: gray[400],
                        },
                    },
                };
            },

            async initViewer(collectionIndex, fileIndex) {
                const key = `${collectionIndex}-${fileIndex}`;
                const containerId = `pdf-viewer-${collectionIndex}-${fileIndex}`;
                const container = document.getElementById(containerId);

                // Synchronous lock: `this.viewers[key]` is only set once the
                // async import below resolves, so without claiming the slot
                // *before* awaiting anything, two near-simultaneous calls
                // (e.g. a stray extra init trigger) can both pass this check
                // and mount two competing viewer instances into one container.
                if (!container || this.viewers[key] || this.status[key] === 'loading') {
                    return;
                }

                const file = this.documentCollections[collectionIndex]?.files?.[fileIndex];

                if (!file) {
                    this.status[key] = 'error';
                    return;
                }

                this.viewers[key] = 'pending';
                this.status[key] = 'loading';

                // Let layout settle (tab switch, container becoming visible,
                // fonts) before measuring the container, so the viewer's
                // first render doesn't compute against a transient 0-size box.
                await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)));

                try {
                    const { default: EmbedPDF, ZoomMode } = await import('https://cdn.jsdelivr.net/npm/@embedpdf/snippet@2/dist/embedpdf.js');

                    const instance = EmbedPDF.init({
                        type: 'container',
                        target: container,
                        src: file.url,
                        theme: this.buildEmbedTheme(this.theme),
                        // `display.fitMode` (used previously) is not a real
                        // EmbedPDF option and was silently ignored, so the
                        // viewer opened at whatever its internal default zoom
                        // is rather than one actually fitted to the container.
                        // `zoom.defaultZoomLevel` is the real switch for this,
                        // and is the same code path the toolbar's own
                        // zoom controls use — which is why manually changing
                        // zoom "fixed" the size.
                        zoom: {
                            defaultZoomLevel: ZoomMode.FitWidth,
                            minZoom: 0.5,
                            maxZoom: 3,
                        },
                        toolbar: {
                            enabled: true,
                            position: 'top',
                            items: ['page-navigation', 'zoom-out', 'zoom-in', 'fit-width', 'download'],
                            labels: {
                                'zoom-in': '{{ __('Zoom In') }}',
                                'zoom-out': '{{ __('Zoom Out') }}',
                                'fit-width': '{{ __('Fit Width') }}',
                                'download': '{{ __('Download') }}',
                            },
                        },
                        layout: { sidebar: { enabled: false } },
                        interaction: {
                            zoom: { min: 0.5, max: 3, step: 0.25, wheel: true, pinch: true },
                            pan: { enabled: true, mode: 'drag' },
                        },
                        display: { pageGap: 8, padding: 16 },
                        annotations: { enabled: false },
                        editing: { enabled: false },
                        features: {
                            annotation: false,
                            shape: false,
                            draw: false,
                            text: false,
                            stamp: false,
                            highlight: false,
                            underline: false,
                            strikeout: false,
                            comment: false,
                            'open-file': false,
                            'close-file': false,
                            save: false,
                            'save-as': false,
                        },
                        menu: { enabled: false },
                    });

                    this.viewers[key] = instance;
                    this.status[key] = 'ready';
                    // Nudge the library to recompute page layout against the
                    // now-settled container size (harmless if unnecessary).
                    window.dispatchEvent(new Event('resize'));
                } catch (error) {
                    console.error(`Failed to initialize PDF viewer for ${key}:`, error);
                    delete this.viewers[key]; // release the lock so a retry can re-init
                    this.status[key] = 'error';
                }
            },
        };
    }
</script>