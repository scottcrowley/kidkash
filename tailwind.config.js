const { colors } = require('tailwindcss/defaultTheme');

module.exports = {
  theme: {
    extend: {
      fontFamily: {
        sans: [
          'Nunito', 
          '-apple-system',
          'BlinkMacSystemFont',
          '"Segoe UI"',
          'Roboto',
          '"Helvetica Neue"',
          'Arial',
          '"Noto Sans"',
          'sans-serif',
          '"Apple Color Emoji"',
          '"Segoe UI Emoji"',
          '"Segoe UI Symbol"',
          '"Noto Color Emoji"',
        ]
      },
      colors: {
        primary: colors.blue,
        secondary: colors.gray,
        success: colors.green,
        warning: colors.orange,
        danger: colors.red,
        error: colors.red,
      }
    },
    customForms: theme => ({
      default: {
        'input, textarea, multiselect, select': {
          fontSize: theme('fontSize.sm'),
        },
        'input' : {
          width: theme('width.full'),
          paddingTop: theme('spacing.1'),
          paddingBottom: theme('spacing.1'),
          lineHeight: theme('lineHeight.relaxed'),
        },
      },
    })
  },
  variants: {},
  plugins: [
    require('@tailwindcss/custom-forms'),
  ]
}
