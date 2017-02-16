# 
# little script to generate random strings
#

def build_files()
  ## how long shall the codes be
  length = 8

  ## how many codes do we need?
  count = 1000

  codes = generate_codes(length, count)

  ## make shure the codes are unique
  codes = make_codes_uniq(count, codes, length)
  write_out(codes)
end


def generate_codes(length, count)
  codes = Array.[]

  for i in 1..count do
    codes.push([*('a'..'z'),*('0'..'9'),*('A'..'Z')].shuffle[0,length].join)
  end

  return codes
end


def make_codes_uniq(count,codes, length)
  codes.uniq!
  if codes.count < count
      puts "dup\n"
      puts codes.count
      count = count - codes.count
      c = generate_codes(length, count)

      c.each{|elem| codes.push(elem)}

      make_codes_uniq(count, codes, length)
  else
      return codes
  end
end

def write_out(codes)
  ## write the codes to csv and txt files
  ["txt", "csv"].each do |f|
    of = File.open("unique-codes." + f , "w")
    out = Array.[]

    case f
      when "txt"
        codes.each {|c| out.push(c.chomp)}
      when "csv"
        ## creates line suited for the rvv-dl application database
        id = 0
        codes.each {|c|
          out.push("\"#{id}\";\"" + c.chomp + "\";\"\";\"\";\"" + Time.now.inspect + "\";\"" + Time.now.inspect + "\"" )
          id += 1
        }
    end

    out.each { |l| of.puts l }
    of.close
  end
end


build_files()
