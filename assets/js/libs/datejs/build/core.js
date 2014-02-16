/**
 * @version: 1.0 Alpha-1
 * @author: Coolite Inc. http://www.coolite.com/
 * @date: 2008-05-13
 * @copyright: Copyright (c) 2006-2008, Coolite Inc. (http://www.coolite.com/). All rights reserved.
 * @license: Licensed under The MIT License. See license.txt and http://www.datejs.com/license/.
 * @website: http://www.datejs.com/
 */
(function() {
	var $D = Date, $P = $D.prototype, $C = $D.CultureInfo, p = function(s, l) {
		if(!l) {l = 2;}
		return("000" + s).slice(l * -1);
	};
	$P.clearTime = function() {
		this.setHours(0);
		this.setMinutes(0);
		this.setSeconds(0);
		this.setMilliseconds(0);
		return this;
	};
	$P.setTimeToNow = function() {
		var n = new Date();
		this.setHours(n.getHours());
		this.setMinutes(n.getMinutes());
		this.setSeconds(n.getSeconds());
		this.setMilliseconds(n.getMilliseconds());
		return this;
	};
	$D.today = function() {return new Date().clearTime();};
	$D.compare = function(date1, date2) {if(isNaN(date1) || isNaN(date2)) {throw new Error(date1 + " - " + date2);} else if(date1 instanceof Date && date2 instanceof Date) {return(date1 < date2) ? -1 : (date1 > date2) ? 1 : 0;} else {throw new TypeError(date1 + " - " + date2);}};
	$D.equals = function(date1, date2) {return(date1.compareTo(date2) === 0);};
	$D.getDayNumberFromName = function(name) {
		var n = $C.dayNames, m = $C.abbreviatedDayNames, o = $C.shortestDayNames, s = name.toLowerCase();
		for(var i = 0; i < n.length; i++) {if(n[i].toLowerCase() == s || m[i].toLowerCase() == s || o[i].toLowerCase() == s) {return i;}}
		return-1;
	};
	$D.getMonthNumberFromName = function(name) {
		var n = $C.monthNames, m = $C.abbreviatedMonthNames, s = name.toLowerCase();
		for(var i = 0; i < n.length; i++) {if(n[i].toLowerCase() == s || m[i].toLowerCase() == s) {return i;}}
		return-1;
	};
	$D.isLeapYear = function(year) {return((year % 4 === 0 && year % 100 !== 0) || year % 400 === 0);};
	$D.getDaysInMonth = function(year, month) {return[31, ($D.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];};
	$D.getTimezoneAbbreviation = function(offset) {
		var z = $C.timezones, p;
		for(var i = 0; i < z.length; i++) {if(z[i].offset === offset) {return z[i].name;}}
		return null;
	};
	$D.getTimezoneOffset = function(name) {
		var z = $C.timezones, p;
		for(var i = 0; i < z.length; i++) {if(z[i].name === name.toUpperCase()) {return z[i].offset;}}
		return null;
	};
	$P.clone = function() {return new Date(this.getTime());};
	$P.compareTo = function(date) {return Date.compare(this, date);};
	$P.equals = function(date) {return Date.equals(this, date || new Date());};
	$P.between = function(start, end) {return this.getTime() >= start.getTime() && this.getTime() <= end.getTime();};
	$P.isAfter = function(date) {return this.compareTo(date || new Date()) === 1;};
	$P.isBefore = function(date) {return(this.compareTo(date || new Date()) === -1);};
	$P.isToday = function() {return this.isSameDay(new Date());};
	$P.isSameDay = function(date) {return this.clone().clearTime().equals(date.clone().clearTime());};
	$P.addMilliseconds = function(value) {
		this.setMilliseconds(this.getMilliseconds() + value);
		return this;
	};
	$P.addSeconds = function(value) {return this.addMilliseconds(value * 1000);};
	$P.addMinutes = function(value) {return this.addMilliseconds(value * 60000);};
	$P.addHours = function(value) {return this.addMilliseconds(value * 3600000);};
	$P.addDays = function(value) {
		this.setDate(this.getDate() + value);
		return this;
	};
	$P.addWeeks = function(value) {return this.addDays(value * 7);};
	$P.addMonths = function(value) {
		var n = this.getDate();
		this.setDate(1);
		this.setMonth(this.getMonth() + value);
		this.setDate(Math.min(n, $D.getDaysInMonth(this.getFullYear(), this.getMonth())));
		return this;
	};
	$P.addYears = function(value) {return this.addMonths(value * 12);};
	$P.add = function(config) {
		if(typeof config == "number") {
			this._orient = config;
			return this;
		}
		var x = config;
		if(x.milliseconds) {this.addMilliseconds(x.milliseconds);}
		if(x.seconds) {this.addSeconds(x.seconds);}
		if(x.minutes) {this.addMinutes(x.minutes);}
		if(x.hours) {this.addHours(x.hours);}
		if(x.weeks) {this.addWeeks(x.weeks);}
		if(x.months) {this.addMonths(x.months);}
		if(x.years) {this.addYears(x.years);}
		if(x.days) {this.addDays(x.days);}
		return this;
	};
	var $y, $m, $d;
	$P.getWeek = function() {
		var a, b, c, d, e, f, g, n, s, w;
		$y = (!$y) ? this.getFullYear() : $y;
		$m = (!$m) ? this.getMonth() + 1 : $m;
		$d = (!$d) ? this.getDate() : $d;
		if($m <= 2) {
			a = $y - 1;
			b = (a / 4 | 0) - (a / 100 | 0) + (a / 400 | 0);
			c = ((a - 1) / 4 | 0) - ((a - 1) / 100 | 0) + ((a - 1) / 400 | 0);
			s = b - c;
			e = 0;
			f = $d - 1 + (31 * ($m - 1));
		} else {
			a = $y;
			b = (a / 4 | 0) - (a / 100 | 0) + (a / 400 | 0);
			c = ((a - 1) / 4 | 0) - ((a - 1) / 100 | 0) + ((a - 1) / 400 | 0);
			s = b - c;
			e = s + 1;
			f = $d + ((153 * ($m - 3) + 2) / 5) + 58 + s;
		}
		g = (a + b) % 7;
		d = (f + g - e) % 7;
		n = (f + 3 - d) | 0;
		if(n < 0) {w = 53 - ((g - s) / 5 | 0);} else if(n > 364 + s) {w = 1;} else {w = (n / 7 | 0) + 1;}
		$y = $m = $d = null;
		return w;
	};
	$P.getISOWeek = function() {
		$y = this.getUTCFullYear();
		$m = this.getUTCMonth() + 1;
		$d = this.getUTCDate();
		return p(this.getWeek());
	};
	$P.setWeek = function(n) {return this.moveToDayOfWeek(1).addWeeks(n - this.getWeek());};
	$D._validate = function(n, min, max, name) {
		if(typeof n == "undefined") {return false;} else if(typeof n != "number") {throw new TypeError(n + " is not a Number.");} else if(n < min || n > max) {throw new RangeError(n + " is not a valid value for " + name + ".");}
		return true;
	};
	$D.validateMillisecond = function(value) {return $D._validate(value, 0, 999, "millisecond");};
	$D.validateSecond = function(value) {return $D._validate(value, 0, 59, "second");};
	$D.validateMinute = function(value) {return $D._validate(value, 0, 59, "minute");};
	$D.validateHour = function(value) {return $D._validate(value, 0, 23, "hour");};
	$D.validateDay = function(value, year, month) {return $D._validate(value, 1, $D.getDaysInMonth(year, month), "day");};
	$D.validateMonth = function(value) {return $D._validate(value, 0, 11, "month");};
	$D.validateYear = function(value) {return $D._validate(value, 0, 9999, "year");};
	$P.set = function(config) {
		if($D.validateMillisecond(config.millisecond)) {this.addMilliseconds(config.millisecond - this.getMilliseconds());}
		if($D.validateSecond(config.second)) {this.addSeconds(config.second - this.getSeconds());}
		if($D.validateMinute(config.minute)) {this.addMinutes(config.minute - this.getMinutes());}
		if($D.validateHour(config.hour)) {this.addHours(config.hour - this.getHours());}
		if($D.validateMonth(config.month)) {this.addMonths(config.month - this.getMonth());}
		if($D.validateYear(config.year)) {this.addYears(config.year - this.getFullYear());}
		if($D.validateDay(config.day, this.getFullYear(), this.getMonth())) {this.addDays(config.day - this.getDate());}
		if(config.timezone) {this.setTimezone(config.timezone);}
		if(config.timezoneOffset) {this.setTimezoneOffset(config.timezoneOffset);}
		if(config.week && $D._validate(config.week, 0, 53, "week")) {this.setWeek(config.week);}
		return this;
	};
	$P.moveToFirstDayOfMonth = function() {return this.set({day: 1});};
	$P.moveToLastDayOfMonth = function() {return this.set({day: $D.getDaysInMonth(this.getFullYear(), this.getMonth())});};
	$P.moveToNthOccurrence = function(dayOfWeek, occurrence) {
		var shift = 0;
		if(occurrence > 0) {shift = occurrence - 1;}
		else if(occurrence === -1) {
			this.moveToLastDayOfMonth();
			if(this.getDay() !== dayOfWeek) {this.moveToDayOfWeek(dayOfWeek, -1);}
			return this;
		}
		return this.moveToFirstDayOfMonth().addDays(-1).moveToDayOfWeek(dayOfWeek, +1).addWeeks(shift);
	};
	$P.moveToDayOfWeek = function(dayOfWeek, orient) {
		var diff = (dayOfWeek - this.getDay() + 7 * (orient || +1)) % 7;
		return this.addDays((diff === 0) ? diff += 7 * (orient || +1) : diff);
	};
	$P.moveToMonth = function(month, orient) {
		var diff = (month - this.getMonth() + 12 * (orient || +1)) % 12;
		return this.addMonths((diff === 0) ? diff += 12 * (orient || +1) : diff);
	};
	$P.getOrdinalNumber = function() {return Math.ceil((this.clone().clearTime() - new Date(this.getFullYear(), 0, 1)) / 86400000) + 1;};
	$P.getTimezone = function() {return $D.getTimezoneAbbreviation(this.getUTCOffset());};
	$P.setTimezoneOffset = function(offset) {
		var here = this.getTimezoneOffset(), there = Number(offset) * -6 / 10;
		return this.addMinutes(there - here);
	};
	$P.setTimezone = function(offset) {return this.setTimezoneOffset($D.getTimezoneOffset(offset));};
	$P.hasDaylightSavingTime = function() {return(Date.today().set({month: 0, day: 1}).getTimezoneOffset() !== Date.today().set({month: 6, day: 1}).getTimezoneOffset());};
	$P.isDaylightSavingTime = function() {return(this.hasDaylightSavingTime() && new Date().getTimezoneOffset() === Date.today().set({month: 6, day: 1}).getTimezoneOffset());};
	$P.getUTCOffset = function() {
		var n = this.getTimezoneOffset() * -10 / 6, r;
		if(n < 0) {
			r = (n - 10000).toString();
			return r.charAt(0) + r.substr(2);
		} else {
			r = (n + 10000).toString();
			return"+" + r.substr(1);
		}
	};
	$P.getElapsed = function(date) {return(date || new Date()) - this;};
	if(!$P.toISOString) {
		$P.toISOString = function() {
			function f(n) {return n < 10 ? '0' + n : n;}

			return'"' + this.getUTCFullYear() + '-' +
				f(this.getUTCMonth() + 1) + '-' +
				f(this.getUTCDate()) + 'T' +
				f(this.getUTCHours()) + ':' +
				f(this.getUTCMinutes()) + ':' +
				f(this.getUTCSeconds()) + 'Z"';
		};
	}
	$P._toString = $P.toString;
	$P.toString = function(format) {
		var x = this;
		if(format && format.length == 1) {
			var c = $C.formatPatterns;
			x.t = x.toString;
			switch(format) {
				case"d":
					return x.t(c.shortDate);
				case"D":
					return x.t(c.longDate);
				case"F":
					return x.t(c.fullDateTime);
				case"m":
					return x.t(c.monthDay);
				case"r":
					return x.t(c.rfc1123);
				case"s":
					return x.t(c.sortableDateTime);
				case"t":
					return x.t(c.shortTime);
				case"T":
					return x.t(c.longTime);
				case"u":
					return x.t(c.universalSortableDateTime);
				case"y":
					return x.t(c.yearMonth);
			}
		}
		var ord = function(n) {
			switch(n * 1) {
				case 1:
				case 21:
				case 31:
					return"st";
				case 2:
				case 22:
					return"nd";
				case 3:
				case 23:
					return"rd";
				default:
					return"th";
			}
		};
		return format ? format.replace(/(\\)?(dd?d?d?|MM?M?M?|yy?y?y?|hh?|HH?|mm?|ss?|tt?|S)/g, function(m) {
			if(m.charAt(0) === "\\") {return m.replace("\\", "");}
			x.h = x.getHours;
			switch(m) {
				case"hh":
					return p(x.h() < 13 ? (x.h() === 0 ? 12 : x.h()) : (x.h() - 12));
				case"h":
					return x.h() < 13 ? (x.h() === 0 ? 12 : x.h()) : (x.h() - 12);
				case"HH":
					return p(x.h());
				case"H":
					return x.h();
				case"mm":
					return p(x.getMinutes());
				case"m":
					return x.getMinutes();
				case"ss":
					return p(x.getSeconds());
				case"s":
					return x.getSeconds();
				case"yyyy":
					return p(x.getFullYear(), 4);
				case"yy":
					return p(x.getFullYear());
				case"dddd":
					return $C.dayNames[x.getDay()];
				case"ddd":
					return $C.abbreviatedDayNames[x.getDay()];
				case"dd":
					return p(x.getDate());
				case"d":
					return x.getDate();
				case"MMMM":
					return $C.monthNames[x.getMonth()];
				case"MMM":
					return $C.abbreviatedMonthNames[x.getMonth()];
				case"MM":
					return p((x.getMonth() + 1));
				case"M":
					return x.getMonth() + 1;
				case"t":
					return x.h() < 12 ? $C.amDesignator.substring(0, 1) : $C.pmDesignator.substring(0, 1);
				case"tt":
					return x.h() < 12 ? $C.amDesignator : $C.pmDesignator;
				case"S":
					return ord(x.getDate());
				default:
					return m;
			}
		}) : this._toString();
	};
}());
