import sys
import pytesseract
import os
from pdf2image import convert_from_path

class processAttachent:

    res = ""
    pdf = convert_from_path('/var/www/html/storage/app/public/'+sys.argv[1])
    for text in pdf:
        res += pytesseract.image_to_string(text)
    print(res)
