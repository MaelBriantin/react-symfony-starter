export enum ButtonType {
  SUBMIT = "submit",
  RESET = "reset",
  BUTTON = "button",
}

interface ButtonProps {
  loading: boolean;
  loadingText?: string;
  type?: ButtonType;
  disabled?: boolean;
  children: React.ReactNode;
}

const Button = ({ loading, loadingText, children, type }: ButtonProps) => {
  return (
    <button
          type={type || ButtonType.BUTTON}
          className="bg-blue-500 text-white rounded-md p-2 mt-4 hover:bg-blue-600"
          disabled={loading}
        >
          {loading && loadingText ? loadingText : children}
    </button>
  );
}

export default Button;