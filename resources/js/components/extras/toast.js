import { useToasts } from 'react-toast-notifications'

export const ToastDemo = ({ content }) => {
  const { addToast } = useToasts()
  return (
    <Button onClick={() => addToast(content, {
      appearance: 'info',
      autoDismiss: false,
    })}>
      Add Toast
    </Button>
  )
}