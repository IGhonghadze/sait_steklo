/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./index.html"],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Manrope', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
        serif: ['"Playfair Display"', 'Georgia', 'serif'],
        mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'monospace'],
      },
      colors: {
        brandGreen: '#16A685',
        brandMint: '#10B981',
        brandCyan: '#38B0E3',
        brandCyanLight: '#0EA5E9',
        crystalCyan: '#38BDF8',
        slateDeep: '#0F172A',
        slateMuted: '#64748B',
      },
      zIndex: {
        'modal': '99999',
      }
    }
  },
  plugins: [],
}
