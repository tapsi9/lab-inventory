import qrcode
import sys

def generate_qr(url, output_file):
    # Generate QR code
    qr = qrcode.make(url)
    
    # Save as PNG file
    qr.save(output_file)
    
if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python generate_qr.py <url> <output_file>")
        sys.exit(1)
        
    url = sys.argv[1]
    output_file = sys.argv[2]
    generate_qr(url, output_file) 