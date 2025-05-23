export enum ButtonType {
  SUBMIT = "submit",
  RESET = "reset",
  BUTTON = "button",
}

export interface ButtonProps {
  loading: boolean;
  loadingText?: string;
  type?: ButtonType;
  disabled?: boolean;
  children: React.ReactNode;
}