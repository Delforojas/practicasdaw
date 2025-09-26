export interface User {
  id: number;
  full_name: string;
  surname?: string;     
  username: string;     
  address?: string;       
  postal_code?: string;   
  city?: string;          
  email: string;
  phone?: string;         
  password?: string;      
  role: string;
  birthdate?: Date;       
  created_at: Date;
  profileImage?: string;
}