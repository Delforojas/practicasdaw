export interface RegisterDTO {
  full_name: string;
  surname?: string;       
  username: string;      
  phone?: string;         
  address?: string;        
  postal_code?: string;   
  city?: string;          
  email: string;
  password: string;
  profileImage?: File;
}