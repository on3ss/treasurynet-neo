@props(['url'])

<div x-data="{
    theme: 'light',
    init() {
        this.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';

        const observer = new MutationObserver(() => {
            this.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            if (this.$refs.pdfViewer.updateTheme) {
                this.$refs.pdfViewer.updateTheme(this.theme);
            }
        });

        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    }
}" class="w-full">
    <div x-ref="pdfViewer" id="pdf-viewer" style="height: 80vh; border-radius: 0.5rem; overflow: hidden;"></div>

    <script type="module">
        import EmbedPDF from 'https://cdn.jsdelivr.net/npm/@embedpdf/snippet@2/dist/embedpdf.js';

        const container = document.getElementById('pdf-viewer');
        const initialTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';

        const viewerConfig = {
            type: 'container',
            target: container,
            src: @js($url),
            theme: {
                preference: initialTheme,
                // Light theme colors
                light: {
                    accent: {
                        primary: '#4f46e5', // Indigo-600 (Filament primary)
                        primaryHover: '#4338ca', // Indigo-700
                        primaryActive: '#3730a3', // Indigo-800
                        primaryLight: '#e0e7ff', // Indigo-100 (subtle backgrounds)
                        primaryForeground: '#ffffff'
                    },
                    surface: {
                        background: '#ffffff',
                        surface: '#f9fafb', // Gray-50
                        surfaceHover: '#f3f4f6', // Gray-100
                        border: '#e5e7eb', // Gray-200
                        text: '#111827', // Gray-900
                        textSecondary: '#6b7280' // Gray-500
                    }
                },
                // Dark theme colors
                dark: {
                    accent: {
                        primary: '#6366f1', // Indigo-500 (lighter for dark mode)
                        primaryHover: '#818cf8', // Indigo-400
                        primaryActive: '#a5b4fc', // Indigo-300
                        primaryLight: '#312e81', // Indigo-900 (subtle backgrounds)
                        primaryForeground: '#ffffff'
                    },
                    surface: {
                        background: '#111827', // Gray-900
                        surface: '#1f2937', // Gray-800
                        surfaceHover: '#374151', // Gray-700
                        border: '#4b5563', // Gray-600
                        text: '#e5e7eb', // Gray-200
                        textSecondary: '#9ca3af' // Gray-400
                    }
                }
            },
            toolbar: {
                enabled: true,
                position: 'top',
                items: [
                    'page-navigation',
                    'zoom-out',
                    'zoom-in',
                    'fit-width',
                    'download',
                ],
                labels: {
                    'zoom-in': 'Zoom In',
                    'zoom-out': 'Zoom Out',
                    'fit-width': 'Fit Width',
                    'download': 'Download',
                }
            },
            layout: {
                sidebar: {
                    enabled: false,
                }
            },
            interaction: {
                zoom: {
                    min: 0.5,
                    max: 3,
                    step: 0.25,
                    wheel: true,
                    pinch: true,
                },
                pan: {
                    enabled: true,
                    mode: 'drag',
                }
            },
            display: {
                pageGap: 8,
                padding: 16,
                fitMode: 'width',
            },
            annotations: {
                enabled: false,
            },
            editing: {
                enabled: false,
            },
            features: {
                'annotation': false,
                'shape': false,
                'draw': false,
                'text': false,
                'stamp': false,
                'highlight': false,
                'underline': false,
                'strikeout': false,
                'comment': false,
                'open-file': false,
                'close-file': false,
                'save': false,
                'save-as': false,
            },
            menu: {
                enabled: false,
            }
        };

        const instance = EmbedPDF.init(viewerConfig);

        // Store instance for theme updates
        container._embedPdfInstance = instance;

        // Expose updateTheme method for Alpine
        container.updateTheme = (newTheme) => {
            const themeConfig = {
                preference: newTheme,
                light: {
                    accent: {
                        primary: '#4f46e5',
                        primaryHover: '#4338ca',
                        primaryActive: '#3730a3',
                        primaryLight: '#e0e7ff',
                        primaryForeground: '#ffffff'
                    },
                    surface: {
                        background: '#ffffff',
                        surface: '#f9fafb',
                        surfaceHover: '#f3f4f6',
                        border: '#e5e7eb',
                        text: '#111827',
                        textSecondary: '#6b7280'
                    }
                },
                dark: {
                    accent: {
                        primary: '#6366f1',
                        primaryHover: '#818cf8',
                        primaryActive: '#a5b4fc',
                        primaryLight: '#312e81',
                        primaryForeground: '#ffffff'
                    },
                    surface: {
                        background: '#111827',
                        surface: '#1f2937',
                        surfaceHover: '#374151',
                        border: '#4b5563',
                        text: '#e5e7eb',
                        textSecondary: '#9ca3af'
                    }
                }
            };

            instance.updateTheme(themeConfig);
        };
    </script>
</div>
