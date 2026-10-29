/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./index.html"],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'Manrope', 'sans-serif'],
        serif: ['Cormorant Garamond', 'serif'],
      },
      colors: {
        silk: '#F8FAFD',
        alabaster: '#FAFDFE',
        slateDeep: '#0F172A',
        slateMuted: '#64748B',
        brandMint: '#10B981',
        brandMintLight: '#34D399',
        brandCyan: '#0EA5E9',
        brandCyanLight: '#38BDF8',
        crystalCyan: '#38BDF8',
        aquaEdge: '#6EE7B7',
        brand: {
          green: '#10B981',
          dark: '#0F172A',
          light: '#ECFFF2'
        }
      },
      zIndex: {
        'modal': '99999',
      }
    }
  },
  plugins: [],
}
